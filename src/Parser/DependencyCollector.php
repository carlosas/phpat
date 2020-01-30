<?php

namespace PhpAT\Parser;

use PhpParser\NameContext;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\PhpDocParser\Parser\TokenIterator;

class DependencyCollector extends NodeVisitorAbstract
{
    /**
     * @return ClassName[]
     */
    private $dependencies = [];
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
    private $context;

    public function __construct(PhpDocParser $docParser, NameContext &$nameContext, bool $ignoreDocBlocks = false)
    {
        $this->docParser = $docParser;
        $this->ignoreDocBlocks = $ignoreDocBlocks;
        $this->context = $nameContext;
    }

    public function beforeTraverse(array $nodes)
    {
        $this->dependencies = [];
    }

    public function leaveNode(Node $node)
    {
        if ($node instanceof Node\Name\FullyQualified) {
            $this->addDependency($node->toString());
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
                    $class = $this->context->getResolvedClassName($nameNode);
                    if ($class !== null) {
                        $this->addDependency($class);
                    }
                }
            }
        }
    }

    /**
     * @return ClassName[]
     */
    public function getDependencies(): array
    {
        return $this->dependencies;
    }

    private function addDependency(string $fqcn): void
    {
        $class = ClassName::createFromFQCN($fqcn);
        if (!isset($this->dependencies[$fqcn]) && $this->isAutoloaded($fqcn) && $class->getNamespace() !== '') {
            $this->dependencies[$fqcn] = $class;
        }
    }

    private function isAutoloaded(string $fqcn): bool
    {
        return class_exists($fqcn) || interface_exists($fqcn) || trait_exists($fqcn);
    }
}
