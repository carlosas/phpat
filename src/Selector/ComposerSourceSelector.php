<?php

declare(strict_types=1);

namespace PhpAT\Selector;

use PhpAT\Parser\AstNode;
use PhpAT\Parser\ClassLike;
use PhpAT\Parser\RegexClassName;

class ComposerSourceSelector implements SelectorInterface
{

    /** @var string */
    private $composerJson;

    /** @var AstNode[] */
    private $astMap;

    protected $includeDev;

    public function __construct(string $composerJson, bool $includeDev)
    {
        $this->composerJson = $composerJson;
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
        $data = json_decode(file_get_contents($this->composerJson), true);

        $namespaces = array_merge(
            array_keys($data['autoload']['psr-0'] ?? []),
            array_keys($data['autoload']['psr-4'] ?? [])
        );

        if ($this->includeDev) {
            $namespaces = array_merge(
                $namespaces,
                array_keys($data['autoload-dev']['psr-0'] ?? []),
                array_keys($data['autoload-dev']['psr-4'] ?? [])
            );
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
        return $this->composerJson;
    }
}
