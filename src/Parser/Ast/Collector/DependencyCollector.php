<?php

namespace PhpAT\Parser\Ast\Collector;

use PhpAT\App\Configuration;
use PhpAT\Parser\Ast\PhpDocTypeResolver;
use PhpAT\Parser\Ast\FullClassName;
use PhpAT\Parser\Relation\Dependency;
use PhpParser\Comment\Doc;
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
    /**
     * @var PhpDocTypeResolver
     */
    private $docTypeResolver;

    public function __construct(
        PhpDocParser $docParser,
        PhpDocTypeResolver $docTypeResolver,
        NameContext $nameContext,
        bool $ignoreDocBlocks = false
    ) {
        $this->docParser = $docParser;
        $this->docTypeResolver = $docTypeResolver;
        $this->nameContext = $nameContext;
        $this->ignoreDocBlocks = $ignoreDocBlocks;
    }

    public function beforeTraverse(array $nodes)
    {
        parent::beforeTraverse($nodes);
        $this->found = [];

        return $nodes;
    }

    public function leaveNode(Node $node)
    {
        if ($node instanceof Node\Name\FullyQualified) {
            $this->addDependency($node->getLine(), $node->toString());
        }

        if (!$this->ignoreDocBlocks && $node->getDocComment() !== null) {
            foreach ($this->extractDocClassNames($node->getDocComment()) as $class) {
                $this->addDependency($node->getLine(), $class);
            }
        }

        return $node;
    }

    private function addDependency(int $line, string $fqcn): void
    {
        $class = FullClassName::createFromFQCN($fqcn);
        if (!array_key_exists($fqcn, $this->found) && $this->isAutoloaded($fqcn)) {
            $this->found[$fqcn] = $class->getFQCN();
            $this->results[] = new Dependency($line, $class);
        }
    }

    private function isAutoloaded(string $fqcn): bool
    {
        return class_exists($fqcn) || interface_exists($fqcn) || trait_exists($fqcn);
    }

    private function extractDocClassNames(Doc $doc): array
    {
        $nodes = $this->docParser->parse(new TokenIterator((new Lexer())->tokenize($doc->getText())));
        foreach ($nodes->getTags() as $tag) {
            if (isset($tag->value->type)) {
                $names = $this->docTypeResolver->resolve($tag->value->type);
                foreach ($names as $name) {
                    $nameNode = strpos($name, '\\') === 0
                        ? new() Node\Name\FullyQualified($name)
                        : new() Node\Name($name);
                    $result[] = $this->nameContext->getResolvedClassName($nameNode);
                }
            }
        }

        return $result ?? [];
    }
}
