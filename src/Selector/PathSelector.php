<?php

declare(strict_types=1);

namespace PhpAT\Selector;

use PhpAT\File\FileFinder;
use PhpAT\Parser\AstNode;
use PhpAT\Parser\ClassName;
use PhpAT\Parser\Collector\ClassNameCollector;
use PhpParser\NodeTraverserInterface;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\Parser;

/**
 * Class PathSelector
 *
 * @package PhpAT\Selector
 */
class PathSelector implements SelectorInterface
{
    private const DEPENDENCIES = [
        FileFinder::class,
        Parser::class,
        NodeTraverserInterface::class,
    ];

    /**
     * @var string
     */
    private $path;
    /**
     * @var FileFinder
     */
    private $fileFinder;
    /**
     * @var AstNode[]
     */
    private $astMap;
    /**
     * @var Parser
     */
    private $parser;
    /**
     * @var NodeTraverserInterface
     */
    private $traverser;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function getDependencies(): array
    {
        return self::DEPENDENCIES;
    }

    public function injectDependencies(array $dependencies): void
    {
        $this->fileFinder = $dependencies[FileFinder::class];
        $this->parser = $dependencies[Parser::class];
        $this->traverser = $dependencies[NodeTraverserInterface::class];
    }

    /**
     * @param AstNode[] $astMap
     */
    public function setAstMap(array $astMap): void
    {
        $this->astMap = $astMap;
    }

    /**
     * @return string[]
     */
    public function select(): array
    {
        foreach ($this->fileFinder->findFiles($this->path) as $file) {
            $nameResolver = new NameResolver();
            $classNameCollector = new ClassNameCollector();
            $filePathname = str_replace('\\', '/', $file->getPathname());
            $parsed = $this->parser->parse(file_get_contents($filePathname));
            $this->traverser->addVisitor($nameResolver);
            $this->traverser->addVisitor($classNameCollector);
            $this->traverser->traverse($parsed);

            /** @var ClassName $name */
            foreach ($classNameCollector->getResult() as $name) {
                $result[$name->getFQCN()] = $name->getFQCN();
            }
        }

        return $result ?? [];
    }
}
