<?php

declare(strict_types=1);

namespace PhpAT\Selector;

use PhpAT\Parser\Ast\SrcNode;
use PhpAT\Parser\Ast\ReferenceMap;
use PhpAT\Parser\Ast\ClassLike;
use PhpAT\Parser\Ast\FullClassName;
use PhpAT\Parser\Relation\Mixin;

class IncludeSelector implements SelectorInterface
{
    private string $fqcn;
    private ?\PhpAT\Parser\Ast\ReferenceMap $map = null;

    public function __construct(string $fqcn)
    {
        $this->fqcn = $fqcn;
    }

    public function getDependencies(): array
    {
        return [];
    }

    public function injectDependencies(array $dependencies): void
    {
    }

    public function setReferenceMap(ReferenceMap $map): void
    {
        $this->map = $map;
    }

    /**
     * @return ClassLike[]
     */
    public function select(): array
    {
        foreach ($this->map->getSrcNodes() as $srcNode) {
            foreach ($srcNode->getRelations() as $relation) {
                if (
                    $relation instanceof Mixin
                    && $this->matchesPattern($relation->relatedClass->getFQCN(), $this->fqcn)
                ) {
                    $result[] = FullClassName::createFromFQCN($srcNode->getClassName());
                }
            }
        }

        return $result ?? [];
    }

    public function getParameter(): string
    {
        return $this->fqcn;
    }

    private function matchesPattern(string $className, string $pattern): bool
    {
        $pattern = preg_replace_callback(
            '/([^*])/',
            function ($m) {
                return preg_quote($m[0], '/');
            },
            $pattern
        );
        $pattern = str_replace('*', '.*', $pattern);

        return (bool) preg_match('/^' . $pattern . '$/i', $className);
    }
}
