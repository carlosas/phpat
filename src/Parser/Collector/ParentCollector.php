<?php

namespace PhpAT\Parser\Collector;

use PhpAT\Parser\ClassMatcher;
use PhpAT\Parser\ClassName;
use PhpParser\Node;

class ParentCollector extends AbstractCollector
{
    /**
     * @var ClassMatcher
     */
    private $matcher;

    public function __construct(ClassMatcher $matcher)
    {
        $this->matcher = $matcher;
    }

    public function leaveNode(Node $node)
    {
        if ($node instanceof Node\Stmt\UseUse) {
            $this->matcher->addDeclaration($node->name, $node->alias);
        }

        if ($node instanceof Node\Stmt\Class_) {
            if (isset($node->extends) && !is_null($node->extends)) {
                $found = $this->matcher->findClass($node->extends->parts);
                if (!is_null($found)) {
                    $this->result[] = ClassName::createFromFQDN($found);
                }
            }
        }
    }
}
