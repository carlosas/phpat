<?php

namespace PhpAT\Parser;

use PhpAT\App\Configuration;
use PhpAT\File\FileFinder;
use PhpParser\ErrorHandler\Throwing;
use PhpParser\NameContext;
use PhpParser\NodeTraverserInterface;
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
     * @var PhpDocParser
     */
    private $phpDocParser;

    public function __construct(
        FileFinder $finder,
        Parser $parser,
        NodeTraverserInterface $traverser,
        PhpDocParser $phpDocParser
    ) {
        $this->finder = $finder;
        $this->parser = $parser;
        $this->traverser = $traverser;
        $this->phpDocParser = $phpDocParser;
    }

    public function build(): array
    {
        $context = new NameContext(new Throwing());
        $nameResolver = new CustomNameResolver($context);
        $this->traverser->addVisitor($nameResolver);
        $nameCollector = new NameCollector();
        $this->traverser->addVisitor($nameCollector);
        $interfaceCollector = new InterfaceCollector();
        $this->traverser->addVisitor($interfaceCollector);
        $traitCollector = new TraitCollector();
        $this->traverser->addVisitor($traitCollector);
        $parentCollector = new ParentCollector();
        $this->traverser->addVisitor($parentCollector);
        $dependencyCollector = new DependencyCollector(
            $this->phpDocParser,
            $context,
            Configuration::getDependencyIgnoreDocBlocks()
        );
        $this->traverser->addVisitor($dependencyCollector);

        $files = $this->finder->findAllFiles(Configuration::getSrcPath());

        /** @var \SplFileInfo $fileInfo */
        foreach ($files as $fileInfo) {
            $parsed = $this->parser->parse(file_get_contents($this->normalizePathname($fileInfo->getPathname())));

            $this->traverser->traverse($parsed);

            $astMap[$nameCollector->getNameString()] = new AstNode(
                $fileInfo,
                $nameCollector->getName(),
                $parentCollector->getParent(),
                $dependencyCollector->getDependencies(),
                $interfaceCollector->getInterfaces(),
                $traitCollector->getTraits()
            );
        }

        return $astMap ?? [];
    }

    private function normalizePathname(string $pathname): string
    {
        return str_replace('\\', '/', $pathname);
    }
}
