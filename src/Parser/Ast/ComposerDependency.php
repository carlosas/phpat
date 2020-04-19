<?php

namespace PhpAT\Parser\Ast;

use PhpAT\Parser\FullClassName;
use PhpAT\Parser\RegexClassName;
use PhpAT\Parser\Relation\AbstractRelation;

class ComposerDependency
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var RegexClassName[]
     */
    private $namespaces;
    /**
     * @var ComposerDependency[]
     */
    private $dependencies;

    /**
     * ComposerDependency constructor.
     * @param string               $name
     * @param RegexClassName[]     $namespaces
     * @param ComposerDependency[] $dependencies
     */
    public function __construct(
        string $name,
        array $namespaces,
        array $dependencies
    ) {
        $this->name = $name;
        $this->namespaces = $namespaces;
        $this->dependencies = $dependencies;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return RegexClassName[]
     */
    public function getNamespaces(): array
    {
        return $this->namespaces;
    }

    /**
     * @return ComposerDependency[]
     */
    public function getDependencies(): array
    {
        return $this->dependencies;
    }
}
