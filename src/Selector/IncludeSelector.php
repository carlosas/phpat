<?php

declare(strict_types=1);

namespace PhpAT\Selector;

use PhpAT\Parser\AstNode;

class IncludeSelector implements SelectorInterface
{
    /**
     * @var string
     */
    private $traitName;
    /**
     * @var AstNode[]
     */
    private $astMap;

    public function __construct(string $traitName)
    {
        $this->traitName = $traitName;
    }

    public function getDependencies(): array
    {
        return [];
    }

    public function injectDependencies(array $dependencies): void
    {
    }

    /**
     * @param AstNode[] $astMap
     */
    public function setAstMap(array $astMap): void
    {
        $this->astMap = $astMap;
    }

    /**
     * @return string[]
     */
    public function select(): array
    {
        foreach ($this->astMap as $astNode) {
            foreach ($astNode->getMixins() as $trait) {
                if ($this->matchesPattern($trait, $this->traitName)) {
                    $result[$astNode->getClassName()] = $astNode->getClassName();
                }
            }
        }

        return $result ?? [];
    }

    /**
     * @return string
     */
    public function getParameter(): string
    {
        return $this->traitName;
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
