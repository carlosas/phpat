<?php

namespace PhpAT\Parser\Collector;

use PhpAT\Parser\ClassMatcher;
use PhpAT\Parser\ClassName;
use PhpParser\Node;

class InterfaceCollector extends AbstractCollector
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
            if (isset($node->implements) && ($node->implements !== null)) {
                foreach ($node->implements as $interface) {
                    $found = $this->matcher->findClass($interface->parts);
                    if ($found !== null) {
                        $this->result[] = ClassName::createFromFQDN($found);
                    }
                }
            }
        }
    }
}
