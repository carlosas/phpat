<?php

namespace PhpAT\Parser\Ast;

use PhpAT\App\Configuration;
use PhpAT\App\Event\FatalErrorEvent;
use PhpAT\App\Exception\FatalErrorException;
use PhpAT\App\Helper\PathNormalizer;
use PhpAT\File\FileFinder;
use PhpAT\Parser\Ast\Classmap\Classmap;
use PhpAT\Parser\Ast\Traverser\TraverseContext;
use PhpAT\Parser\Ast\Traverser\TraverserFactory;
use PhpAT\Parser\ComposerFileParser;
use PhpAT\Parser\ComposerParser;
use PhpAT\PhpStubsMap\PhpStubsMap;
use PhpParser\Parser;
use Psr\EventDispatcher\EventDispatcherInterface;

class MapBuilder
{
    private FileFinder $finder;
    private Parser $parser;
    private TraverserFactory $traverserFactory;
    private EventDispatcherInterface $eventDispatcher;
    private ComposerParser $composerParser;
    private ComposerFileParser $composerFileParser;
    private Configuration $configuration;

    public function __construct(
        FileFinder $finder,
        Parser $parser,
        TraverserFactory $traverserFactory,
        EventDispatcherInterface $eventDispatcher,
        ComposerParser $composerParser,
        ComposerFileParser $composerFileParser,
        Configuration $configuration
    ) {
        $this->finder             = $finder;
        $this->parser             = $parser;
        $this->traverserFactory   = $traverserFactory;
        $this->eventDispatcher    = $eventDispatcher;
        $this->composerParser     = $composerParser;
        $this->composerFileParser = $composerFileParser;
        $this->configuration      = $configuration;
    }

    public function build(): ReferenceMap
    {
        return new ReferenceMap($this->buildSrcMap(), $this->buildExtensionMap(), $this->buildComposerMap());
    }

    /*
     * @return array<string, SrcNode>
     */
    private function buildSrcMap(): array
    {
        $files     = $this->getFilesToParse();
        $traverser = $this->traverserFactory->create();

        foreach ($files as $file) {
            $pathname = PathNormalizer::normalizePathname($file);
            $parsed   = $this->parser->parse(file_get_contents($pathname));
            TraverseContext::startFile($pathname);
            $traverser->traverse($parsed);
        }

        return Classmap::getClassmap();
    }

    /**
     * @return array<ClassLike>
     */
    private function buildExtensionMap(): array
    {
        return array_map(
            function (string $class) {
                return new FullClassName($class);
            },
            array_keys(PhpStubsMap::CLASSES)
        );
    }

    /**
     * @throws FatalErrorException
     * @return array<string, ComposerPackage>
     */
    private function buildComposerMap(): array
    {
        $packages = $this->configuration->getComposerConfiguration();

        $result = [];
        foreach ($packages as $alias => $files) {
            $composerJson = $files['json'];
            $composerLock = $files['lock'] ?? substr($composerJson, 0, -5) . '.lock';
            $this->assertComposerPackage($alias, $composerJson, $composerLock);

            try {
                $parsed = $this->composerFileParser->parse($composerJson, $composerLock);
            } catch (\Throwable $e) {
                $this->eventDispatcher->dispatch(
                    new FatalErrorEvent('Error parsing "' . $alias . '" composer files')
                );
                throw new FatalErrorException();
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

    /**
     * @param array<string> $namespaces
     * @return array<ClassLike>
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

    /**
     * @throws FatalErrorException
     */
    private function assertComposerPackage(string $alias, string $composerJson, string $composerLock): void
    {
        if (!is_file($composerJson)) {
            $error = new FatalErrorEvent('Composer package "' . $alias . '" is not properly configured');
            $this->eventDispatcher->dispatch($error);
            throw new FatalErrorException();
        }

        if (!is_file($composerLock)) {
            $error = new FatalErrorEvent('Unable to find the composer package "' . $alias . '" lock file');
            $this->eventDispatcher->dispatch($error);
            throw new FatalErrorException();
        }
    }

    /**
     * @throws \Exception
     * @return array<string>
     */
    private function getFilesToParse(): array
    {
        $files = [];
        foreach (array_keys($this->configuration->getComposerConfiguration()) as $package) {
            $files = $this->composerParser->getFilesToAutoload($package, false);
        }

        foreach ($this->configuration->getParserInclude() as $path) {
            $files = array_merge(
                $files,
                array_values(
                    array_map(
                        fn (\SplFileInfo $f) => $f->getPathname(),
                        $this->finder->findPhpFilesInPath($path, $this->configuration->getParserExclude())
                    )
                )
            );
        }

        return $files;
    }
}
