<?php

declare(strict_types=1);

namespace PhpAT\Selector;

use PhpAT\App\Configuration;
use PhpAT\File\FileFinder;
use PhpAT\Parser\Ast\ClassLike;
use PhpAT\Parser\Ast\FullClassName;
use PhpAT\Parser\Ast\ReferenceMap;

/**
 * Class PathSelector
 *
 * @package PhpAT\Selector
 */
class PathSelector implements SelectorInterface
{
    private const DEPENDENCIES = [
        Configuration::class
    ];

    private string $path;
    private ?ReferenceMap $map = null;
    private Configuration $configuration;

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
        $this->configuration = $dependencies[Configuration::class];
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
        $path = $this->configuration->getRootPath() . '/' . $this->path;
        foreach ($this->map->getSrcNodes() as $srcNode) {
            if ($this->matchesPattern($srcNode->getFilePathname(), $path)) {
                $result[] = FullClassName::createFromFQCN($srcNode->getClassName());
            }
        }

        return $result ?? [];
    }

    public function getParameter(): string
    {
        return $this->path;
    }

    private function matchesPattern(string $path, string $pattern): bool
    {
        $pattern = preg_replace_callback(
            '/([^*])/',
            function ($m) {
                return preg_quote($m[0], '/');
            },
            $pattern
        );
        $pattern = str_replace('*', '.*', $pattern);

        return (bool) preg_match('/^' . $pattern . '$/i', $path);
    }
}
