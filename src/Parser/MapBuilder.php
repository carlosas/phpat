<?php

namespace PhpAT\Parser;

use PhpAT\App\Configuration;
use PhpAT\File\FileFinder;
use PhpAT\Parser\Collector\AstNodesCollector;
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
     * @var AstNodesCollector
     */
    private $astNodesCollector;

    public function __construct(
        FileFinder $finder,
        Parser $parser,
        NodeTraverserInterface $traverser,
        PhpDocParser $phpDocParser
    ) {
        $this->finder = $finder;
        $this->parser = $parser;
        $this->traverser = $traverser;
        $this->astNodesCollector = new AstNodesCollector($phpDocParser, new ClassMatcher(), false);
    }

    public function build(): array
    {
        $this->traverser->addVisitor($this->astNodesCollector);

        $files = $this->finder->findAllFiles(Configuration::getSrcPath());

        /** @var \SplFileInfo $fileInfo */
        foreach ($files as $fileInfo) {
            $parsed = $this->parser->parse(file_get_contents($this->normalizePathname($fileInfo->getPathname())));

            $this->traverser->traverse($parsed);

            $astMap[] = new AstNode(
                $fileInfo,
                $this->astNodesCollector->getClassNames()[0],
                $this->astNodesCollector->getParents()[0] ?? null,
                $this->astNodesCollector->getDependencies(),
                $this->astNodesCollector->getInterfaces(),
                $this->astNodesCollector->getTraits()
            );

            $this->astNodesCollector->reset();
        }

        $this->traverser->removeVisitor($this->astNodesCollector);
var_dump($astMap); die;
        return $astMap ?? [];
    }

    private function normalizePathname(string $pathname): string
    {
        return str_replace('\\', '/', $pathname);
    }
}
