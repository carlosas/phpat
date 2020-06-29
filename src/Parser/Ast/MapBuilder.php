<?php

namespace PhpAT\Parser\Ast;

use PhpAT\App\Configuration;
use PhpAT\App\Event\ErrorEvent;
use PhpAT\File\FileFinder;
use PhpAT\Parser\Ast\Collector\DependencyCollector;
use PhpAT\Parser\Ast\Collector\InterfaceCollector;
use PhpAT\Parser\Ast\Collector\ClassNameCollector;
use PhpAT\Parser\Ast\Collector\NamespacedNameCollector;
use PhpAT\Parser\Ast\Collector\ParentCollector;
use PhpAT\Parser\Ast\Collector\TraitCollector;
use PhpAT\Parser\ClassLike;
use PhpParser\ErrorHandler\Throwing;
use PhpParser\NameContext;
use PhpParser\Parser;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use Psr\EventDispatcher\EventDispatcherInterface;

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
     * @var NodeTraverser
     */
    private $traverser;
    /**
     * @var PhpDocParser
     */
    private $phpDocParser;
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;
    /**
     * @var Configuration
     */
    private $configuration;

    public function __construct(
        FileFinder $finder,
        Parser $parser,
        NodeTraverser $traverser,
        PhpDocParser $phpDocParser,
        EventDispatcherInterface $eventDispatcher,
        Configuration $configuration
    ) {
        $this->finder = $finder;
        $this->parser = $parser;
        $this->traverser = $traverser;
        $this->phpDocParser = $phpDocParser;
        $this->eventDispatcher = $eventDispatcher;
        $this->configuration = $configuration;
    }

    public function build(): ReferenceMap
    {
        return new ReferenceMap($this->buildSrcMap(), $this->buildExtensionMap());
    }

    private function buildSrcMap(): array
    {
        $this->traverser->reset();
        $nameContext  = new NameContext(new Throwing());
        $nameResolver = new NameResolver($nameContext);
        $this->traverser->addVisitor($nameResolver);
        $nameCollector = new NamespacedNameCollector();
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
            $this->configuration->getIgnoreDocBlocks()
        );
        $this->traverser->addVisitor($dependencyCollector);

        $files = $this->finder->findPhpFilesInPath($this->configuration->getSrcPath());

        /** @var \SplFileInfo $fileInfo */
        foreach ($files as $fileInfo) {
            $parsed = $this->parser->parse(file_get_contents($this->normalizePathname($fileInfo->getPathname())));
            if ($parsed === null) {
                $this->eventDispatcher->dispatch(
                    new ErrorEvent($this->normalizePathname($fileInfo->getPathname()) . ' could not be parsed')
                );
                continue;
            }

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

    /**
     * @return ClassLike[]
     */
    private function buildExtensionMap(): array
    {
        $nameCollector = new ClassNameCollector();
        $this->traverser->reset();
        $this->traverser->addVisitor($nameCollector);

        $files = $this->finder->findPhpFilesInPath($this->configuration->getPhpStormStubsPath());

        /** @var \SplFileInfo $fileInfo */
        foreach ($files as $fileInfo) {
            $parsed = $this->parser->parse(file_get_contents($this->normalizePathname($fileInfo->getPathname())));
            if ($parsed === null) {
                $this->eventDispatcher->dispatch(
                    new ErrorEvent($this->normalizePathname($fileInfo->getPathname()) . ' could not be parsed')
                );
                continue;
            }

            $this->traverser->traverse($parsed);
        }

        return $nameCollector->getNames();
    }

    private function normalizePathname(string $pathname): string
    {
        return str_replace('\\', '/', $pathname);
    }
}
