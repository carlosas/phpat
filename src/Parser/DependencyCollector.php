<?php

namespace PhpAT\Parser;

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
     * @var ClassMatcher
     */
    private $matcher;
    /**
     * @var string
     */
    private $previousNodeType;

    public function __construct(PhpDocParser $docParser, ClassMatcher $matcher, bool $ignoreDocBlocks = false)
    {
        $this->docParser = $docParser;
        $this->ignoreDocBlocks = $ignoreDocBlocks;
        $this->matcher = $matcher;
    }

    public function beforeTraverse(array $nodes)
    {
        $this->dependencies = [];
        $this->matcher->reset();
    }

    public function enterNode(Node $node)
    {
        if ($node instanceof Node\Stmt\Namespace_) {
            $this->matcher->saveNamespace($node->name->toString());
        }

        if ($node instanceof Node\Stmt\UseUse) {
            $this->matcher->addDeclaration(implode('\\', $node->name->parts), $node->getAlias()->name);
        }
    }

    public function leaveNode(Node $node)
    {
        if (
            $node instanceof Node\Name\FullyQualified
            && (
                class_exists($node->toString())
                || interface_exists($node->toString())
                || trait_exists($node->toString())
            )
        ) {
            $this->addDependency($node->toString());
        }

        if (!$this->ignoreDocBlocks && $node->getDocComment() !== null) {
            $doc = $node->getDocComment()->getText();
            $nodes = $this->docParser->parse(new TokenIterator((new Lexer())->tokenize($doc)));
            foreach ($nodes->getTags() as $tag) {
                if (isset($tag->value->type->name)) {
                    $type = $tag->value->type->name;
                    $class = $this->matcher->findClass(explode('\\', $type));
                    if (!is_null($class)) {
                        $this->addDependency($class);
                    }
                }
            }
        }
        
        $this->previousNodeType = get_class($node);
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
        if (!isset($this->dependencies[$fqcn])) {
            $this->dependencies[$fqcn] = ClassName::createFromFQCN($fqcn);
        }
    }
}
