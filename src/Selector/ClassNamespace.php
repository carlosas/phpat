<?php

declare(strict_types=1);

namespace PHPat\Selector;

use function extractNamespaceFromFQCN;
use PHPStan\Reflection\ClassReflection;
use function trimSeparators;

class ClassNamespace implements SelectorInterface
{
    private string $namespace;
    private bool $isRegex;

    public function __construct(string $namespace)
    {
        $this->namespace = $namespace;
        $this->isRegex   = isRegularExpression($namespace);
    }

    public function getName(): string
    {
        return $this->namespace;
    }

    public function matches(ClassReflection $classReflection): bool
    {
        $namespace = extractNamespaceFromFQCN($classReflection->getName());

        if ($this->isRegex) {
            return $this->matchesRegex($namespace);
        }

        return str_starts_with(trimSeparators($namespace), trimSeparators($this->namespace));
    }

    private function matchesRegex(string $namespace): bool
    {
        return (
            preg_match($this->namespace, trimSeparators($namespace)) === 1
            || preg_match($this->namespace, $namespace)              === 1
        );
    }
}
