<?php

declare(strict_types=1);

namespace PHPatOld\Selector;

use PHPatOld\App\Helper\PathNormalizer;
use PHPatOld\File\FileFinder;
use PHPatOld\Parser\Ast\ClassLike;
use PHPatOld\Parser\Ast\FullClassName;
use PHPatOld\Parser\Ast\ReferenceMap;

/**
 * Class PathSelector
 *
 * @package PHPat\Selector
 */
class PathSelector implements Selector
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
