<?php declare(strict_types=1);

namespace PhpAT\Rule\Type;

use PhpAT\File\FileFinder;
use PhpAT\Parser\DependencyExtractor;
use PhpAT\Parser\NamespaceExtractor;
use PhpParser\NodeTraverserInterface;
use PhpParser\Parser;

class Dependency implements RuleType
{
    private $traverser;
    private $finder;
    private $parser;

    public function __construct(FileFinder $finder, Parser $parser, NodeTraverserInterface $traverser)
    {
        $this->finder = $finder;
        $this->parser = $parser;
        $this->traverser = $traverser;
    }

    public function validate(array $parsedClass, array $params): bool
    {
        $dependencyExtractor = new DependencyExtractor();
        $this->traverser->addVisitor($dependencyExtractor);
        $this->traverser->traverse($parsedClass);
        $dependencies = $dependencyExtractor->getResult();
        $this->traverser->removeVisitor($dependencyExtractor);

        $excluded = $params['excluding'] ?? [];
        $filesFound = [];
        foreach ($params['files'] as $file) {
            $found = $this->finder->findFiles($file, $excluded);
            foreach ($found as $f) {
                $filesFound[] = $f;
            }
        }

        $namespaceExtractor = new NamespaceExtractor();
        $this->traverser->addVisitor($namespaceExtractor);

        /** @var \SplFileInfo $file */
        foreach ($filesFound as $file) {
            $parsedFile = $this->parser->parse(file_get_contents($file->getPathname()));
            $this->traverser->traverse($parsedFile);
        }

        /** @var \PhpAT\Parser\Dependency $dependency */
        foreach ($dependencies as $dependency) {
            if (in_array($dependency->getPathname(), $namespaceExtractor->getResult())) {
                return true;
            }
        }

        return false;
    }

    public function getMessageVerb(): string
    {
        return 'depend on';
    }
}
