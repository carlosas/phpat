<?php

declare(strict_types=1);

namespace PhpAT\Selector;

use PhpAT\Parser\Ast\ReferenceMap;
use PhpAT\Parser\ClassLike;
use PhpAT\Parser\ComposerFileParser;
use PhpAT\Parser\RegexClassName;

class ComposerDependencySelector implements SelectorInterface
{
    private $astMap;
    /** @var ReferenceMap */
    private $map;
    /** @var string */
    private $composerAlias;

    public function __construct(string $composerFileAlias)
    {
        $this->composerAlias = $composerFileAlias;
    }

    public function getDependencies(): array
    {
        return [];
    }

    public function injectDependencies(array $dependencies)
    {
    }

    /** @param ReferenceMap $map */
    public function setReferenceMap(ReferenceMap $map): void
    {
        $this->map = $map;
    }

    /** @return ClassLike[] */
    public function select(): array
    {
        $module = $this->map->getComposerMap()[$this->composerAlias] ?? null;
        if ($module === null) {
            return [];
        }

        $namespaceMap = array_filter($module->getAllPackagesNamespaces());

        $result = [];
        foreach ($module->getDeepDependencies() as $direct => $indirects) {
            $result[$direct] = $namespaceMap[$direct] ?? null;
            foreach ($indirects as $indirect) {
                $result[$indirect] = $namespaceMap[$indirect] ?? null;
            }
        }

        return array_merge(...array_values(array_filter($result)));
    }

    public function getParameter(): string
    {
        return $this->composerAlias;
    }
}
