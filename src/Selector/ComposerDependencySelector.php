<?php

declare(strict_types=1);

namespace PhpAT\Selector;

use PhpAT\Parser\ClassLike;
use PhpAT\Parser\RegexClassName;

class ComposerDependencySelector extends ComposerSourceSelector implements SelectorInterface
{

    /** @var ComposerFileParser */
    private $composer;


    public function __construct(string $composerJson, string $composerLock, bool $includeDev)
    {
        parent::__construct($composerJson, $includeDev);
        $this->composer = new ComposerFileParser($composerJson, $composerLock);
    }

    /** @return ClassLike[] */
    public function select(): array
    {
        $namespaces = $this->composer->getDeepRequirementNamespaces($this->includeDev);
        $dependencySelectors = array_map(
            function (string $namespace) {
                return new RegexClassName($namespace . '*');
            },
            $namespaces
        );

        return array_merge($dependencySelectors, parent::select());
    }

    public function getParameter(): string
    {
        return sprintf('%s (%s)', $this->composer->getComposerFilePath(), $this->composer->getLockFilePath());
    }
}
