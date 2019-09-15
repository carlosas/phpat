<?php

namespace PhpAT\Parser\Collector;

use PhpAT\Parser\ClassName;
use PhpParser\Node;

class InterfaceCollector extends AbstractCollector
{
    /** @var ClassName[] $declaredClasses */
    private $declaredClasses = [];

    public function leaveNode(Node $node)
    {
        if ($node instanceof Node\Stmt\UseUse) {
            $this->saveClassDeclaration($node);
        }

        if ($node instanceof Node\Stmt\Class_) {
            if (isset($node->implements) && !is_null($node->implements)) {
                foreach ($node->implements as $interface) {
                    $this->result[] = $this->matchClassName($interface->toString());
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

    private function saveClassDeclaration(Node\Stmt\UseUse $node): void
    {
        //TODO: Resolve class aliases
        $this->declaredClasses[] = ClassName::createFromFQDN($node->name->toString());
    }
}
