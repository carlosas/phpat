<?php

namespace PhpAT\Parser\Collector;

use PhpAT\Parser\ClassMatcher;
use PhpAT\Parser\ClassName;
use PhpParser\Node;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\PhpDocParser\Parser\TokenIterator;

class AstNodesCollector extends AbstractCollector
{
    /**
     * @var ClassMatcher
     */
    private $matcher;
    /**
     * @var bool
     */
    private $ignoreDocBlocks;
    /**
     * @var PhpDocParser
     */
    private $docParser;
    /**
     * @var array
     */
    private $stringDependencies = [];
    /**
     * @var array
     */
    private $classNames = [];
    /**
     * @var array
     */
    private $dependencies = [];
    /**
     * @var array
     */
    private $interfaces = [];
    /**
     * @var array
     */
    private $parents = [];
    /**
     * @var array
     */
    private $traits = [];

    public function __construct(PhpDocParser $docParser, ClassMatcher $matcher, bool $ignoreDocBlocks)
    {
        $this->docParser = $docParser;
        $this->matcher = $matcher;
        $this->ignoreDocBlocks = $ignoreDocBlocks;
    }

    public function leaveNode(Node $node)
    {
        if ($node instanceof Node\Stmt\Namespace_) {
            $this->matcher->saveNamespace($node->name->toString());
            foreach ($node->stmts as $stmt) {
                if (isset($stmt->name) && isset($stmt->name->name)) {
                    $this->classNames[] = new ClassName($node->name->toString(), $stmt->name->name);
                }
            }
        } elseif ($node instanceof Node\Stmt\UseUse) {
            $this->matcher->addDeclaration($node->name, $node->alias);
        } elseif ($node instanceof Node\Name\FullyQualified) {
            $this->saveDependencyIfNotPresent($node->toString());
        } elseif ($node instanceof Node\Name) {
            $found = $this->matcher->findClass($node->parts);
            if ($found !== null) {
                $this->saveDependencyIfNotPresent($found);
            }
        } elseif (!$this->ignoreDocBlocks && $node->getDocComment() !== null) {
            $doc = $node->getDocComment()->getText();
            $nodes = $this->docParser->parse(new TokenIterator((new Lexer())->tokenize($doc)));
            foreach ($nodes->getTags() as $tag) {
                if (isset($tag->value->type->name)) {
                    $type = $tag->value->type->name;
                    $class = $this->matcher->findClass(explode('\\', $type)) ?? $type;
                    $this->saveDependencyIfNotPresent($class);
                }
            }
        } elseif ($node instanceof Node\Stmt\Class_) {
            if (isset($node->implements) && ($node->implements !== null)) {
                foreach ($node->implements as $interface) {
                    $found = $this->matcher->findClass($interface->parts);
                    if ($found !== null) {
                        $this->interfaces[] = ClassName::createFromFQCN($found);
                    }
                }
            }
            if (isset($node->extends) && ($node->extends !== null)) {
                $found = $this->matcher->findClass($node->extends->parts);
                if ($found !== null) {
                    $this->parents[] = ClassName::createFromFQCN($found);
                }
            }
        } elseif ($node instanceof Node\Stmt\TraitUse) {
            if (isset($node->traits) && ($node->traits !== null)) {
                foreach ($node->traits as $trait) {
                    $found = $this->matcher->findClass($trait->parts);
                    if ($found !== null) {
                        $this->traits[] = ClassName::createFromFQCN($found);
                    }
                }
            }
        }
    }

    public function reset(): void
    {
        $this->classNames = [];
        $this->dependencies = [];
        $this->interfaces = [];
        $this->parents = [];
        $this->matcher->reset();
    }

    /**
     * @return array
     */
    public function getClassNames(): array
    {
        return $this->classNames;
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return $this->dependencies;
    }

    /**
     * @return array
     */
    public function getInterfaces(): array
    {
        return $this->interfaces;
    }

    /**
     * @return array
     */
    public function getParents(): array
    {
        return $this->parents;
    }

    /**
     * @return array
     */
    public function getTraits(): array
    {
        return $this->traits;
    }

    private function saveDependencyIfNotPresent(string $fqcn)
    {
        if (array_search($fqcn, $this->stringDependencies) === false) {
            $this->stringDependencies[] = $fqcn;
            $this->dependencies[] = ClassName::createFromFQCN($fqcn);
        }
    }
}
