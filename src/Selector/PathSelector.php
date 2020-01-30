<?php

declare(strict_types=1);

namespace PhpAT\Selector;

use PhpAT\File\FileFinder;
use PhpAT\Parser\AstNode;
use PhpParser\NodeTraverserInterface;
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
            $filePathname = str_replace('\\', '/', $file->getPathname());

            foreach ($this->astMap as $astNode) {
                if ($astNode->getFilePathname() === $filePathname) {
                    $result[$astNode->getClassName()] = $astNode->getClassName();
                }
            }
        }

        return $result ?? [];
    }

    /**
     * @return string
     */
    public function getParameter(): string
    {
        return $this->path;
    }
}
