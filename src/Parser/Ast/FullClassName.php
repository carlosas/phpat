<?php

namespace PhpAT\Parser\Ast;

class FullClassName implements ClassLike
{
    private string $namespace;
    private string $name;
    private string $fqcn;

    private function __construct(string $namespace, string $name, string $fqcn)
    {
        $this->namespace = $namespace;
        $this->name = $name;
        $this->fqcn = $fqcn;
    }

    public static function createFromFQCN(string $fqcn): self
    {
        $parts = explode('\\', ltrim($fqcn, '\\'));
        $name = array_pop($parts);
        $normalizedFqcn = empty($parts) ? $name : $fqcn;

        return new self(implode('\\', $parts), $name, $normalizedFqcn);
    }

    public function getNamespace(): string
    {
        return $this->namespace;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getFQCN(): string
    {
        return $this->fqcn;
    }

    public function matches(string $name): bool
    {
        return $this->getFQCN() === $name;
    }

    public function getMatchingNodes(array $nodes): array
    {
        return array_key_exists($this->getFQCN(), $nodes) ? [$nodes[$this->getFQCN()]] : [];
    }

    public function toString(): string
    {
        return $this->getFQCN();
    }
}
