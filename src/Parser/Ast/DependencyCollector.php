<?php

namespace PhpAT\Parser\Ast;

use PhpAT\Parser\ClassName;
use PhpAT\Parser\Relation\Dependency;
use PhpParser\NameContext;
use PhpParser\Node;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\PhpDocParser\Parser\TokenIterator;

class DependencyCollector extends AbstractRelationCollector
{
    /**
     * @var PhpDocParser
     */
    private $docParser;
    /**
     * @var bool
     */
    private $ignoreDocBlocks;
    /**
     * @var NameContext
     */
    private $nameContext;
    /**
     * @var string[]
     */
    private $found = [];

    public function __construct(PhpDocParser $docParser, NameContext &$nameContext, bool $ignoreDocBlocks = false)
    {
        $this->docParser = $docParser;
        $this->ignoreDocBlocks = $ignoreDocBlocks;
        $this->nameContext = $nameContext;
    }

    public function beforeTraverse(array $nodes)
    {
        parent::beforeTraverse($nodes);
        $this->found = [];
    }

    public function leaveNode(Node $node)
    {
        if ($node instanceof Node\Name\FullyQualified) {
            $this->addDependency($node->getLine(), $node->toString());
        }

        if (!$this->ignoreDocBlocks && $node->getDocComment() !== null) {
            $doc = $node->getDocComment()->getText();
            $nodes = $this->docParser->parse(new TokenIterator((new Lexer())->tokenize($doc)));
            foreach ($nodes->getTags() as $tag) {
                if (isset($tag->value->type->name)) {
                    $name = $tag->value->type->name;
                    $nameNode = strpos($name, '\\') === 0
                        ? new Node\Name\FullyQualified($name)
                        : new Node\Name($name);
                    $class = $this->nameContext->getResolvedClassName($nameNode);
                    if ($class !== null) {
                        $this->addDependency($node->getLine(), $class);
                    }
                }
            }
        }
    }

    private function addDependency(int $line, string $fqcn): void
    {
        $class = ClassName::createFromFQCN($fqcn);
        if (!array_key_exists($fqcn, $this->found) && $this->isAutoloaded($fqcn) && $class->getNamespace() !== '') {
            $this->found[$fqcn] = $class->getFQCN();
            $this->results[] = new Dependency($line, $class);
        }
    }

    private function isAutoloaded(string $fqcn): bool
    {
        return class_exists($fqcn) || interface_exists($fqcn) || trait_exists($fqcn);
    }
}
