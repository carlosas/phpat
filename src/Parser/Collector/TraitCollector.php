<?php

namespace PhpAT\Parser\Collector;

use PhpAT\Parser\ClassMatcher;
use PhpAT\Parser\ClassName;
use PhpParser\Node;

class TraitCollector extends AbstractCollector
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

        if ($node instanceof Node\Stmt\TraitUse) {
            if (isset($node->traits) && ($node->traits !== null)) {
                foreach ($node->traits as $trait) {
                    $found = $this->matcher->findClass($trait->parts);
                    if ($found !== null) {
                        $this->result[] = ClassName::createFromFQDN($found);
                    }
                }
            }
        }
    }
}
