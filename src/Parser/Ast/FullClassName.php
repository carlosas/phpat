<?php

namespace PhpAT\Parser\Ast;

class FullClassName implements ClassLike
{
    private ?string $namespace = null;
    private ?string $name = null;
    private string $fqcn;

    public function __construct(string $fqcn)
    {
        $this->fqcn = $fqcn;
    }

    public function getNamespace(): string
    {
        if ($this->namespace === null) {
            $this->namespace = $this->splitFQCN()[0];
        }

        return $this->namespace;
    }

    public function getName(): string
    {
        if ($this->name === null) {
            $this->name = $this->splitFQCN()[1];
        }

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

    /**
     * @return array<int, string>
     */
    private function splitFQCN(): array
    {
        $parts = explode('\\', ltrim($this->fqcn, '\\'));
        $name  = array_pop($parts);

        $this->namespace = implode('\\', $parts);
        $this->name = $name;

        return [$this->namespace, $this->name];
    }
}
