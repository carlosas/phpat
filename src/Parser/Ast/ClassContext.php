<?php

namespace PhpAT\Parser\Ast;

class ClassContext
{
    /** @var string */
    private $namespace;
    /** @var string[] */
    private $namespaceAliases;

    public function __construct(string $namespace, array $namespaceAliases = [])
    {
        $this->namespace = $namespace !== 'global' && $namespace !== 'default'
            ? trim($namespace, '\\')
            : '';

        foreach ($namespaceAliases as $alias => $fqnn) {
            if ($fqnn[0] === '\\') {
                $fqnn = substr($fqnn, 1);
            }

            if ($fqnn[strlen($fqnn) - 1] === '\\') {
                $fqnn = substr($fqnn, 0, -1);
            }

            $namespaceAliases[$alias] = $fqnn;
        }

        $this->namespaceAliases = $namespaceAliases;
    }

    public function getNamespace(): string
    {
        return $this->namespace;
    }

    public function getNamespaceAliases(): array
    {
        return $this->namespaceAliases;
    }
}
