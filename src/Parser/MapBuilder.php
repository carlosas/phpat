<?php

namespace PhpAT\Parser;

use PhpAT\App\Configuration;
use PhpAT\File\FileFinder;
use PhpAT\Parser\Collector\AbstractCollector;
use PhpAT\Parser\Collector\ClassNameCollector;
use PhpAT\Parser\Collector\DependencyCollector;
use PhpAT\Parser\Collector\InterfaceCollector;
use PhpAT\Parser\Collector\ParentCollector;
use PhpParser\NodeTraverserInterface;
use PhpParser\Parser;
use PHPStan\PhpDocParser\Parser\PhpDocParser;

class MapBuilder
{
    private $namespace = '';
    private $declarations = [];
    /**
     * @var FileFinder
     */
    private $finder;
    /**
     * @var Parser
     */
    private $parser;
    /**
     * @var NodeTraverserInterface
     */
    private $traverser;
    /**
     * @var ClassNameCollector
     */
    private $classNameCollector;
    /**
     * @var ParentCollector
     */
    private $parentCollector;
    /**
     * @var InterfaceCollector
     */
    private $interfaceCollector;
    /**
     * @var DependencyCollector
     */
    private $dependencyCollector;

    public function __construct(
        FileFinder $finder,
        Parser $parser,
        NodeTraverserInterface $traverser,
        PhpDocParser $phpDocParser
    ) {
        $this->finder = $finder;
        $this->parser = $parser;
        $this->traverser = $traverser;
        $this->classNameCollector = new ClassNameCollector();
        $this->parentCollector = new ParentCollector(new ClassMatcher());
        $this->interfaceCollector = new InterfaceCollector(new ClassMatcher());
        $this->dependencyCollector = new DependencyCollector($phpDocParser, new ClassMatcher(), false);
    }

    public function build()
    {
        $files = $this->finder->findAllFiles(Configuration::getSrcPath());

        $this->traverser->addVisitor($this->classNameCollector);
        $this->traverser->addVisitor($this->parentCollector);
        $this->traverser->addVisitor($this->interfaceCollector);
        $this->traverser->addVisitor($this->dependencyCollector);

        /** @var \SplFileInfo $file */
        foreach ($files as $file) {
            $parsed = $this->parser->parse(file_get_contents($file->getPathname()));
            $this->traverser->traverse($parsed);

            $classInfo = $this->buildClassInfo($file);
            if ($classInfo !== null) {
                $classes[] = $classInfo;
            }

            $this->classNameCollector->reset();
            $this->parentCollector->reset();
            $this->interfaceCollector->reset();
            $this->dependencyCollector->reset();
        }

        $this->traverser->removeVisitor($this->classNameCollector);
        $this->traverser->removeVisitor($this->parentCollector);
        $this->traverser->removeVisitor($this->interfaceCollector);
        $this->traverser->removeVisitor($this->dependencyCollector);

        /** @var ClassInfo $classInfo */
        foreach ($classes ?? [] as $classInfo) {
            var_dump($classInfo->jsonSerialize());
        }
    }

    private function buildClassInfo(\SplFileInfo $fileInfo): ?ClassInfo
    {
        if (!isset($this->classNameCollector->getResult()[0])) {
            return null;
        }

        $classInfo = new ClassInfo(
            $fileInfo,
            $this->classNameCollector->getResult()[0],
            null,
            $this->dependencyCollector->getResult(),
            $this->interfaceCollector->getResult(),
            []
        );

        return $classInfo;
    }
}
