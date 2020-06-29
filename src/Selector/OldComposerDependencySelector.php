<?php

declare(strict_types=1);

namespace PhpAT\Selector;

use PhpAT\Parser\Ast\ReferenceMap;
use PhpAT\Parser\ClassLike;
use PhpAT\Parser\ComposerFileParser;
use PhpAT\Parser\RegexClassName;

class OldComposerDependencySelector implements SelectorInterface
{
    /** @var ReferenceMap */
    private $map;
    /** @var ComposerFileParser */
    private $composer;
    /** @var bool */
    private $includeDev;

    public function __construct(string $composerJson, string $composerLock, bool $includeDev)
    {
        $this->composer = new ComposerFileParser($composerJson, $composerLock);
        $this->includeDev = $includeDev;
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
        $namespaces = $this->composer->getDeepRequirementNamespaces(false);
        if ($this->includeDev) {
            $namespaces = array_merge($namespaces, $this->composer->getDeepRequirementNamespaces(true));
        }

        return array_map(
            function (string $namespace) {
                return new RegexClassName($namespace . '*');
            },
            $namespaces
        );
    }

    public function getParameter(): string
    {
        return sprintf('%s (%s)', $this->composer->getComposerFilePath(), $this->composer->getLockFilePath());
    }
}
