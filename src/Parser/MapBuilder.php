<?php

namespace PhpAT\Parser;

use PhpAT\App\Configuration;
use PhpAT\File\FileFinder;
use PhpAT\Parser\Collector\ClassNameCollector;
use PhpAT\Parser\Collector\DependencyCollector;
use PhpAT\Parser\Collector\InterfaceCollector;
use PhpAT\Parser\Collector\ParentCollector;
use PhpAT\Parser\Collector\TraitCollector;
use PhpParser\NodeTraverserInterface;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\Parser;
use PHPStan\PhpDocParser\Parser\PhpDocParser;

class MapBuilder
{
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
    /**
     * @var NameResolver
     */
    private $nameResolver;

    public function __construct(
        FileFinder $finder,
        Parser $parser,
        NodeTraverserInterface $traverser,
        PhpDocParser $phpDocParser
    ) {
        $this->finder = $finder;
        $this->parser = $parser;
        $this->traverser = $traverser;
        $this->nameResolver = new NameResolver();
        $this->classNameCollector = new ClassNameCollector();
        $this->parentCollector = new ParentCollector(new ClassMatcher());
        $this->interfaceCollector = new InterfaceCollector(new ClassMatcher());
        $this->dependencyCollector = new DependencyCollector($phpDocParser, new ClassMatcher(), false);
        $this->traitCollector = new TraitCollector(new ClassMatcher());
    }

    public function build(): array
    {
        $files = $this->finder->findAllFiles(Configuration::getSrcPath());

        $this->traverser->addVisitor($this->classNameCollector);
        $this->traverser->addVisitor($this->parentCollector);
        $this->traverser->addVisitor($this->interfaceCollector);
        $this->traverser->addVisitor($this->dependencyCollector);

        /** @var \SplFileInfo $file */
        foreach ($files as $file) {
            $parsed = $this->parser->parse(file_get_contents($this->normalizePathname($file->getPathname())));

            $this->traverser->traverse($parsed);

            $astNode = $this->buildAstNode($file);

            if ($astNode->jsonSerialize()['classname'] === 'Tests\PhpAT\functional\PHP7\fixtures\Dependency\Constructor') {
                var_dump($astNode->jsonSerialize()); die;
            }


            if ($astNode !== null) {
                $astMap[] = $astNode;
            }
        }

        $this->traverser->removeVisitor($this->nameResolver);
        $this->traverser->removeVisitor($this->classNameCollector);
        $this->traverser->removeVisitor($this->parentCollector);
        $this->traverser->removeVisitor($this->interfaceCollector);
        $this->traverser->removeVisitor($this->dependencyCollector);

        return $astMap ?? [];
    }

    private function buildAstNode(\SplFileInfo $fileInfo): ?AstNode
    {
        if (!isset($this->classNameCollector->getResult()[0])) {
            return null;
        }

//        echo '------------------------------------------------' . PHP_EOL;
//        echo $fileInfo->getPathname() . PHP_EOL;
//var_dump($this->dependencyCollector->getResult());
//        echo '------------------------------------------------' . PHP_EOL;
        $node = new AstNode(
            $fileInfo,
            $this->classNameCollector->getResult()[0],
            $this->parentCollector->getResult()[0] ?? null,
            $this->dependencyCollector->getResult(),
            $this->interfaceCollector->getResult(),
            []
        );

        return $node;
    }

    private function normalizePathname(string $pathname): string
    {
        return str_replace('\\', '/', $pathname);
    }
}
