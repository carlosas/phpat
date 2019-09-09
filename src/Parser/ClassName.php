<?php

namespace PhpAT\Parser;

class ClassName
{
    private $namespace;
    private $name;

    public function __construct(string $namespace, string $name)
    {
        $this->namespace = $namespace;
        $this->name = $name;
    }

    public static function createFromFQDN(string $fqdn): self
    {
        $parts = explode('\\', $fqdn);
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

    public function getFQDN(): string
    {
        return $this->getNamespace() . '\\' . $this->getName();
    }
}
