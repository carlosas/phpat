<?php

namespace PhpAT\Parser\Ast;

use MCStreetguy\ComposerParser\Factory as ComposerParser;
use PhpAT\App\Configuration;
use PhpAT\File\FileFinder;
use PhpAT\Parser\Ast\Collector\DependencyCollector;
use PhpAT\Parser\Ast\Collector\InterfaceCollector;
use PhpAT\Parser\Ast\Collector\NameCollector;
use PhpAT\Parser\Ast\Collector\ParentCollector;
use PhpAT\Parser\Ast\Collector\TraitCollector;
use PhpAT\Parser\Ast\Composer\ComposerPackage;
use PhpAT\Parser\ComposerFileParser;
use PhpParser\ErrorHandler\Throwing;
use PhpParser\NameContext;
use PhpParser\NodeTraverserInterface;
use PhpParser\Parser;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use Psr\EventDispatcher\EventDispatcherInterface;
use Tightenco\Collect\Support\Collection;

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
    /**
     * @var ComposerFileParser
     */
    private $composerParser;
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;
    private static $packageNames;

    public function __construct(
        FileFinder $finder,
        Parser $parser,
        NodeTraverserInterface $traverser,
        ComposerFileParser $composerParser,
        PhpDocParser $phpDocParser,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->finder = $finder;
        $this->parser = $parser;
        $this->traverser = $traverser;
        $this->composerParser = $composerParser;
        $this->phpDocParser = $phpDocParser;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function build(): ReferenceMap
    {
        return new ReferenceMap($this->buildSrcMap());
    }

    private function buildSrcMap(): array
    {
        $nameContext  = new NameContext(new Throwing());
        $nameResolver = new NameResolver($nameContext);
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
            new PhpDocTypeResolver(),
            $nameContext,
            Configuration::getDependencyIgnoreDocBlocks()
        );
        $this->traverser->addVisitor($dependencyCollector);

        $files = $this->finder->findPhpFilesInPath(Configuration::getSrcPath());

        /** @var \SplFileInfo $fileInfo */
        foreach ($files as $fileInfo) {
            $parsed = $this->parser->parse(file_get_contents($this->normalizePathname($fileInfo->getPathname())));

            $this->traverser->traverse($parsed);

            $srcMap[$nameCollector->getNameString()] = new SrcNode(
                $fileInfo,
                $nameCollector->getName(),
                array_merge(
                    $parentCollector->getResults(),
                    $dependencyCollector->getResults(),
                    $interfaceCollector->getResults(),
                    $traitCollector->getResults()
                )
            );
        }

        return $srcMap ?? [];
    }

    private function normalizePathname(string $pathname): string
    {
        return str_replace('\\', '/', $pathname);
    }
}
