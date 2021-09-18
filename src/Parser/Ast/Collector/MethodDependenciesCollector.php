<?php

namespace PhpAT\Parser\Ast\Collector;

use PhpAT\App\Configuration;
use PhpAT\Parser\Ast\ClassContext;
use PhpAT\Parser\Ast\FullClassName;
use PhpAT\Parser\Ast\Type\PhpStanDocTypeNodeResolver;
use PhpAT\Parser\Relation\AbstractRelation;
use PhpAT\Parser\Relation\Dependency;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

/**
 * Class MethodDependenciesCollector
 * @package PhpAT\Parser\Ast\Collector
 * Based on maglnet/ComposerRequireChecker UsedSymbolCollector
 * Copyright (c) 2015 Marco Pivetta | MIT License
 */
class MethodDependenciesCollector extends NodeVisitorAbstract
{
    /** @var Configuration */
    private $configuration;
    /** @var PhpStanDocTypeNodeResolver */
    private $docTypeResolver;
    /** @var ClassContext */
    private $context;

    /** @var AbstractRelation[] */
    protected $results = [];

    public function __construct(
        Configuration $configuration,
        PhpStanDocTypeNodeResolver $docTypeResolver,
        ClassContext $context
    ) {
        $this->configuration = $configuration;
        $this->docTypeResolver = $docTypeResolver;
        $this->context = $context;
    }

    /**
     * @return AbstractRelation[]
     */
    public function getResults(): array
    {
        return $this->results;
    }

    public function beforeTraverse(array $nodes)
    {
        $this->results = [];

        return $nodes;
    }

    public function leaveNode(Node $node)
    {
        $this->recordClassExpressionUsage($node);
        $this->recordCatchUsage($node);
        $this->recordExtendsUsage($node);
        $this->recordImplementsUsage($node);
        $this->recordDocBlockUsage($node);

        return $node;
    }

    private function addDependency(string $fqdn, int $line): void
    {
        $className = FullClassName::createFromFQCN($fqdn);
        $this->results[] = new Dependency($line, $className);
    }

    private function recordClassExpressionUsage(Node $node)
    {
        if (
            (
                $node instanceof Node\Expr\StaticCall
                || $node instanceof Node\Expr\StaticPropertyFetch
                || $node instanceof Node\Expr\ClassConstFetch
                || $node instanceof Node\Expr\New_
                || $node instanceof Node\Expr\Instanceof_
            )
            && $node->class instanceof Node\Name
        ) {
            $this->addDependency((string) $node->class, $node->getStartLine());
        }
    }

    private function recordCatchUsage(Node $node)
    {
        if ($node instanceof Node\Stmt\Catch_) {
            foreach ($node->types as $type) {
                $this->addDependency((string) $type, $node->getStartLine());
            }
        }
    }

    private function recordExtendsUsage(Node $node)
    {
        if ($node instanceof Node\Stmt\Class_ || $node instanceof Node\Stmt\Interface_) {
            foreach (array_filter([$node->extends]) as $extends) {
                $this->addDependency((string) $extends, $node->getStartLine());
            }
        }
    }

    private function recordImplementsUsage(Node $node)
    {
        if ($node instanceof Node\Stmt\Class_) {
            foreach (array_filter($node->implements) as $implements) {
                $this->addDependency((string) $implements, $node->getStartLine());
            }
        }
    }

    private function recordDocBlockUsage(Node $node)
    {
        $doc = $node->getDocComment();
        if ($doc === null) {
            return;
        }

        $names = $this->docTypeResolver->getBlockClassNames($this->context, $doc->getText());
        foreach ($names as $name) {
            $this->addDependency($name, $doc->getStartLine());
        }
    }
}
