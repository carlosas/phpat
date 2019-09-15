<?php declare(strict_types=1);

namespace PhpAT\Rule\Type;

use PhpAT\File\FileFinder;
use PhpAT\Parser\ClassName;
use PhpAT\Parser\ClassNameExtractor;
use PhpAT\Parser\InterfaceExtractor;
use PhpParser\NodeTraverserInterface;
use PhpParser\Parser;

class Composition implements RuleType
{
    private $traverser;
    private $finder;
    private $parser;
    private $parsedClassNamespace;
    private $parsedClassInterfaces;

    public function __construct(FileFinder $finder, Parser $parser, NodeTraverserInterface $traverser)
    {
        $this->finder = $finder;
        $this->parser = $parser;
        $this->traverser = $traverser;
    }

    public function validate(array $parsedClass, array $params): bool
    {
        $this->extractParsedClassInfo($parsedClass);

        $filesFound = [];
        foreach ($params['files'] as $file) {
            $found = $this->finder->findFiles($file);
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

        if (empty($this->parsedClassInterfaces)) {
            return false;
        }

        /** @var ClassName $className */
        foreach ($classNameExtractor->getResult() as $className) {
            if (!in_array($className->getFQDN(), $this->parsedClassInterfaces)) {
                return false;
            }
        }

        return true;
    }

    public function getMessageVerb(): string
    {
        return 'implement';
    }

    private function extractParsedClassInfo(array $parsedClass): void
    {
        $interfaceExtractor = new InterfaceExtractor();
        $classNameExtractor = new ClassNameExtractor();

        $this->traverser->addVisitor($interfaceExtractor);
        $this->traverser->addVisitor($classNameExtractor);
        $this->traverser->traverse($parsedClass);
        $this->traverser->removeVisitor($interfaceExtractor);

        /** @var ClassName $cName */
        $cName = $classNameExtractor->getResult()[0];
        $this->parsedClassNamespace = $cName->getNamespace();
        $this->parsedClassInterfaces = $interfaceExtractor->getResult();

        /** @var ClassName $v */
        foreach ($this->parsedClassInterfaces as $k => $v) {
            if (empty($v->getNamespace())) {
                $className = new ClassName($this->parsedClassNamespace, $v->getName());
                $this->parsedClassInterfaces[$k] = $className->getFQDN();
            } else {
                $this->parsedClassInterfaces[$k] = $v->getFQDN();
            }
        }
    }
}
