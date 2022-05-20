<?php

namespace PHPat\Selector;

use PHPStan\Reflection\ClassReflection;
use function extractNamespaceFromFQCN;
use function removePrefixAndSuffixSeparators;

class ClassNamespace implements SelectorInterface
{
    private string $namespace;

    public function __construct(string $namespace)
    {
        $this->namespace = $namespace;
    }

    public function matches(ClassReflection $classReflection): bool
    {
        return str_starts_with(
            extractNamespaceFromFQCN($classReflection->getName()),
            removePrefixAndSuffixSeparators($this->namespace)
        );
    }
}
