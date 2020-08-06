<?php

namespace PhpAT\Parser\Ast\Collector;

use PhpAT\App\Configuration;

use PhpAT\Parser\Ast\FullClassName;
use PhpAT\Parser\Ast\PhpDocTypeResolver;
use PhpAT\Parser\Ast\PhpType;
use PhpAT\Parser\Relation\AbstractRelation;
use PhpAT\Parser\Relation\Dependency;
use phpDocumentor\Reflection\Types\Context;
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
    /**
     * @var Configuration
     */
    private $configuration;
    /**
     * @var PhpDocTypeResolver
     */
    private $docTypeResolver;
    /**
     * @var string[]
     */
    private $aliases;

    /** @var AbstractRelation[] */
    protected $results = [];

    public function beforeTraverse(array $nodes)
    {
        $nodes = parent::beforeTraverse($nodes);
        $this->results = [];

        return $nodes;
    }

    /**
     * @return AbstractRelation[]
     */
    public function getResults(): array
    {
        return $this->results;
    }

    public function __construct(
        Configuration $configuration,
        PhpDocTypeResolver $docTypeResolver,
        Context $context
    ) {
        $this->configuration = $configuration;
        $this->docTypeResolver = $docTypeResolver;
        $this->aliases = $context->getNamespaceAliases();
    }

    public function leaveNode(Node $node)
    {
        $this->recordClassExpressionUsage($node);
        $this->recordClassExpressionUsage($node);
        $this->recordCatchUsage($node);
        //$this->recordFunctionCallUsage($node);

        return $node;
    }

    private function addDependency(Node\Name $symbol, int $line): void
    {
        $className = FullClassName::createFromFQCN((string) $symbol);
        if (!PhpType::isBuiltinType($className->getFQCN()) && !PhpType::isSpecialType($className->getFQCN())) {
            $this->results[] = new Dependency($line, $className);
        }
    }

    public function recordClassExpressionUsage(Node $node)
    {
        if (($node instanceof Node\Expr\StaticCall
                || $node instanceof Node\Expr\StaticPropertyFetch
                || $node instanceof Node\Expr\ClassConstFetch
                || $node instanceof Node\Expr\New_
                || $node instanceof Node\Expr\Instanceof_
            )
            && $node->class instanceof Node\Name
        ) {
            $this->addDependency($node->class, $node->getStartLine());
        }
    }

    public function recordCatchUsage(Node $node)
    {
        if ($node instanceof Node\Stmt\Catch_) {
            foreach ($node->types as $type) {
                $this->addDependency($type, $node->getStartLine());
            }
        }
    }

    public function recordFunctionCallUsage(Node $node)
    {
        if ($node instanceof Node\Expr\FuncCall
            && $node->name instanceof Node\Name
        ) {
            $this->addDependency($node->name, $node->getStartLine());
        }
    }
}
