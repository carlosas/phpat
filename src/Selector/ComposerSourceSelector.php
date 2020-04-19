<?php

declare(strict_types=1);

namespace PhpAT\Selector;

use PhpAT\Parser\Ast\AstNode;
use PhpAT\Parser\Ast\ReferenceMap;
use PhpAT\Parser\ClassLike;
use PhpAT\Parser\RegexClassName;

class ComposerSourceSelector implements SelectorInterface
{
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

        return $module->getMainAutoloadNamespaces();
    }

    public function getParameter(): string
    {
        return $this->composerAlias;
    }
}
