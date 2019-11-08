<?php

namespace PhpAT\Parser\Collector;

use PhpAT\Parser\ClassMatcher;
use PhpAT\Parser\ClassName;
use PhpParser\Node;

class DependencyCollector extends AbstractCollector
{
    /**
     * @var ClassMatcher 
     */
    private $matcher;
    private $dependencies = [];

    public function __construct(ClassMatcher $matcher)
    {
        $this->matcher = $matcher;
    }

    public function leaveNode(Node $node)
    {
        if ($node instanceof Node\Stmt\UseUse) {
            $this->matcher->addDeclaration($node->name, $node->alias);
        } elseif ($node instanceof Node\Name\FullyQualified) {
            $this->saveResultIfNotPresent($node->toString());
        } elseif ($node instanceof Node\Name) {
            $found = $this->matcher->findClass($node->parts);
            if (!is_null($found)) {
                $this->saveResultIfNotPresent($found);
            }
        }
    }

    private function saveResultIfNotPresent(string $fqdn)
    {
        if (false === array_search($fqdn, $this->dependencies)) {
            $this->dependencies[] = $fqdn;
            $this->result[] = ClassName::createFromFQDN($fqdn);
        }
    }
}
