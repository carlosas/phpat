<?php

namespace PhpAT\Parser\Ast;

use PhpAT\App\Configuration;
use PhpAT\App\Event\ErrorEvent;
use PhpAT\App\Event\FatalErrorEvent;
use PhpAT\File\FileFinder;
use PhpAT\Parser\Ast\Collector\DependencyCollector;
use PhpAT\Parser\Ast\Collector\InterfaceCollector;
use PhpAT\Parser\Ast\Collector\ClassNameCollector;
use PhpAT\Parser\Ast\Collector\NamespacedNameCollector;
use PhpAT\Parser\Ast\Collector\ParentCollector;
use PhpAT\Parser\Ast\Collector\TraitCollector;
use PhpAT\Parser\ComposerFileParser;
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
     * @var ComposerFileParser
     */
    private $composerFileParser;
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
        ComposerFileParser $composerFileParser,
        Configuration $configuration
    ) {
        $this->finder = $finder;
        $this->parser = $parser;
        $this->traverser = $traverser;
        $this->phpDocParser = $phpDocParser;
        $this->eventDispatcher = $eventDispatcher;
        $this->composerFileParser = $composerFileParser;
        $this->configuration = $configuration;
    }

    public function build(): ReferenceMap
    {
        return new ReferenceMap($this->buildSrcMap(), $this->buildExtensionMap(), $this->buildComposerMap());
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

    /** @return ComposerPackage[] */
    private function buildComposerMap(): array
    {
        $packages = $this->configuration->getComposerConfiguration();

        $result = [];
        foreach ($packages as $alias => $files) {
            if (
                !isset($files['json'])
                || !is_file($files['json'])
                || !is_file($files['lock'] ?? substr($files['json'], 0, -5) . '.lock')
            ) {
                $error = new FatalErrorEvent('Composer package "' . $alias . '" is not properly configured');
                $this->eventDispatcher->dispatch($error);
            }

            try {
                $parsed = $this->composerFileParser->parse($files['json'], $files['lock']);
            } catch (\Throwable $e) {
                $error = new FatalErrorEvent('Error parsing "' . $alias . '" composer files');
                $this->eventDispatcher->dispatch($error);
                continue;
            }

            $result[$alias] = new ComposerPackage(
                $alias,
                $this->convertNamespacesToClassLikes($parsed->getNamespaces(false)),
                $this->convertNamespacesToClassLikes($parsed->getNamespaces(true)),
                $this->convertNamespacesToClassLikes($parsed->getDeepRequirementNamespaces(false)),
                $this->convertNamespacesToClassLikes($parsed->getDeepRequirementNamespaces(true))
            );
        }

        return $result;
    }

    private function normalizePathname(string $pathname): string
    {
        return str_replace('\\', '/', $pathname);
    }

    /**
     * @param string[] $array
     * @return ClassLike[]
     */
    private function convertNamespacesToClassLikes(array $namespaces): array
    {
        return array_map(
            function (string $namespace) {
                return new RegexClassName($namespace . '*');
            },
            $namespaces
        );
    }
}
