<?php

namespace PhpAT\Parser\Collector;

use PhpAT\Parser\ClassName;
use PhpParser\Node;

class TraitCollector extends AbstractCollector
{
    /** @var ClassName[] $declaredClasses */
    private $declaredClasses = [];

    public function leaveNode(Node $node)
    {
        if ($node instanceof Node\Stmt\UseUse) {
            $this->saveClassUsage($node);
        }

        if ($node instanceof Node\Stmt\TraitUse) {
            if (isset($node->traits) && !is_null($node->traits)) {
                foreach ($node->traits as $trait) {
                    $this->result[] = $this->matchClassName($trait->toString());
                }
            }
        }
    }

    private function matchClassName(string $name): ClassName
    {
        foreach ($this->declaredClasses as $class) {
            if ($name === $class->getName()) {
                return $class;
            }
        }

        //TODO: Resolve mixed namespace and class name like use Asdf\Qwer; new Qwer\Classname();
        //TODO: Append current class namespace

        return new ClassName('', $name);
    }

    private function saveClassUsage(Node\Stmt\UseUse $node): void
    {
        //TODO: Resolve class aliases
        $this->declaredClasses[] = ClassName::createFromFQDN($node->name->toString());
    }
}
