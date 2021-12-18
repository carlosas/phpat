<?php

declare(strict_types=1);

namespace PhpAT\Selector;

use PhpAT\App\Helper\PathNormalizer;
use PhpAT\File\FileFinder;
use PhpAT\Parser\Ast\ReferenceMap;
use PhpAT\Parser\Ast\ClassLike;
use PhpAT\Parser\Ast\FullClassName;

/**
 * Class PathSelector
 *
 * @package PhpAT\Selector
 */
class PathSelector implements SelectorInterface
{
    private const DEPENDENCIES = [
        FileFinder::class
    ];

    private string $path;
    private FileFinder $fileFinder;
    private ?ReferenceMap $map = null;

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
    }

    public function setReferenceMap(ReferenceMap $map): void
    {
        $this->map = $map;
    }

    /**
     * @return array<ClassLike>
     */
    public function select(): array
    {
        foreach ($this->fileFinder->findSrcFiles($this->path) as $file) {
            $filePathname = PathNormalizer::normalizePathname($file->getPathname());

            foreach ($this->map->getSrcNodes() as $srcNode) {
                if ($srcNode->getFilePathname() === $filePathname) {
                    $result[] = FullClassName::createFromFQCN($srcNode->getClassName());
                }
            }
        }

        return $result ?? [];
    }

    public function getParameter(): string
    {
        return $this->path;
    }
}
