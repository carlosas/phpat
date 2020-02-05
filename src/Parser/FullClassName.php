<?php

namespace PhpAT\Parser;

class FullClassName implements ClassLike
{
    private $namespace;
    private $name;

    public function __construct(string $namespace, string $name)
    {
        $this->namespace = $namespace;
        $this->name = $name;
    }

    public static function createFromFQCN(string $fqcn): self
    {
        $parts = explode('\\', $fqcn);
        $name = array_pop($parts);

        return new self(implode('\\', $parts), $name);
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
        return (empty($this->getNamespace()))
            ? $this->getName()
            : $this->getNamespace() . '\\' . $this->getName();
    }

    public function matches(string $name): bool
    {
        return $this->getFQCN() === $name;
    }

    public function toString(): string
    {
        return $this->getFQCN();
    }
}
