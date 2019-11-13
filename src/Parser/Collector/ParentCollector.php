<?php declare(strict_types=1);

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
            if (isset($node->extends) && ($node->extends !== null)) {
                $found = $this->matcher->findClass($node->extends->parts);
                if ($found !== null) {
                    $this->result[] = ClassName::createFromFQDN($found);
                }
            }
        }
    }
}
