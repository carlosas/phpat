<?php

declare(strict_types=1);

namespace PhpAT\Selector;

use PhpAT\Parser\AstNode;

class ClassNameSelector implements SelectorInterface
{
    /**
     * @var string
     */
    private $fqcn;
    /**
     * @var AstNode[]
     */
    private $astMap;

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
            if ($this->matchesPattern($astNode->getClassName(), $this->fqcn)) {
                $result[$astNode->getClassName()] = $astNode->getClassName();
            }
        }

        return $result ?? [];
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
