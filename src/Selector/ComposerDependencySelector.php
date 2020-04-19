<?php

declare(strict_types=1);

namespace PhpAT\Selector;

use PhpAT\Parser\Ast\AstNode;
use PhpAT\Parser\ClassLike;
use PhpAT\Parser\RegexClassName;

class ComposerDependencySelector implements SelectorInterface
{
    /** @var ComposerFileParser */
    private $composer;
    /**
     * @var bool
     */
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

    /** @param AstNode[] $astMap */
    public function setAstMap(array $astMap)
    {
        $this->astMap = $astMap;
    }

    /** @return ClassLike[] */
    public function select(): array
    {
        $namespaces = $this->composer->getDeepRequirementNamespaces($this->includeDev);

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
