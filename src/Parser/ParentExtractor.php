<?php

namespace PhpAT\Parser;

use PhpParser\Node;

class ParentExtractor extends AbstractExtractor
{
    /** @var ClassName[] $declaredClasses */
    private $declaredClasses = [];

    public function leaveNode(Node $node)
    {
        if ($node instanceof Node\Stmt\UseUse) {
            $this->saveClassDeclaration($node);
        }

        if ($node instanceof Node\Stmt\Class_) {
            if (isset($node->extends)) {
                $this->result[] = $this->matchClassName(implode('\\', $node->extends->parts));
            }
        }
    }

    private function matchClassName(string $name): string
    {
        foreach ($this->declaredClasses as $class) {
            if ($name === $class->getName()) {
                return $class->getFQDN();
            }
        }

        //TODO: Resolve mixed namespace and class name like use Asdf\Qwer; new Qwer\Classname();
        //TODO: Append current class namespace

        return $name;
    }

    private function saveClassDeclaration(Node\Stmt\UseUse $node): void
    {
        //TODO: Resolve class aliases
        $this->declaredClasses[] = ClassName::createFromFQDN(implode('\\', $node->name->parts));
    }
}
