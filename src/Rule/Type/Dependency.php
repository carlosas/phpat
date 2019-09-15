<?php declare(strict_types=1);

namespace PhpAT\Rule\Type;

use PhpAT\File\FileFinder;
use PhpAT\Parser\ClassName;
use PhpAT\Parser\ClassNameExtractor;
use PhpAT\Parser\DependencyExtractor;
use PhpParser\NodeTraverserInterface;
use PhpParser\Parser;

class Dependency implements RuleType
{
    private $traverser;
    private $finder;
    private $parser;
    private $parsedClassNamespace;
    private $parsedClassDependencies;

    public function __construct(FileFinder $finder, Parser $parser, NodeTraverserInterface $traverser)
    {
        $this->finder = $finder;
        $this->parser = $parser;
        $this->traverser = $traverser;
    }

    public function validate(array $parsedClass, array $params): bool
    {
        $this->extractParsedClassInfo($parsedClass);

        $excluded = $params['excluding'] ?? [];
        $filesFound = [];
        foreach ($params['files'] as $file) {
            $found = $this->finder->findFiles($file, $excluded);
            foreach ($found as $f) {
                $filesFound[] = $f;
            }
        }

        $classNameExtractor = new ClassNameExtractor();
        $this->traverser->addVisitor($classNameExtractor);

        /** @var \SplFileInfo $file */
        foreach ($filesFound as $file) {
            $parsed = $this->parser->parse(file_get_contents($file->getPathname()));
            $this->traverser->traverse($parsed);
        }
        $this->traverser->removeVisitor($classNameExtractor);

        if (empty($this->parsedClassDependencies)) {
            return false;
        }

        /** @var ClassName $className */
        foreach ($classNameExtractor->getResult() as $className) {
            if (!in_array($className->getFQDN(), $this->parsedClassDependencies)) {
                return false;
            }
        }

        return true;
    }

    public function getMessageVerb(): string
    {
        return 'depend on';
    }

    private function extractParsedClassInfo(array $parsedClass): void
    {
        $dependencyExtractor = new DependencyExtractor();
        $classNameExtractor = new ClassNameExtractor();

        $this->traverser->addVisitor($dependencyExtractor);
        $this->traverser->addVisitor($classNameExtractor);
        $this->traverser->traverse($parsedClass);
        $this->traverser->removeVisitor($dependencyExtractor);

        /** @var ClassName $cName */
        $cName = $classNameExtractor->getResult()[0];
        $this->parsedClassNamespace = $cName->getNamespace();
        $this->parsedClassDependencies = $dependencyExtractor->getResult();

        /** @var ClassName $v */
        foreach ($this->parsedClassDependencies as $k => $v) {
            if (empty($v->getNamespace())) {
                $className = new ClassName($this->parsedClassNamespace, $v->getName());
                $this->parsedClassDependencies[$k] = $className->getFQDN();
            } else {
                $this->parsedClassDependencies[$k] = $v->getFQDN();
            }
        }
    }
}
