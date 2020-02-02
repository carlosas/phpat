<?php

declare(strict_types=1);

namespace PhpAT\Rule\Assertion\Inheritance;

use PHPAT\EventDispatcher\EventDispatcher;
use PhpAT\Parser\AstNode;
use PhpAT\Parser\Relation\Inheritance;
use PhpAT\Rule\Assertion\Assertion;
use PhpAT\Statement\Event\StatementNotValidEvent;
use PhpAT\Statement\Event\StatementValidEvent;

class CanOnlyExtend implements Assertion
{
    private $eventDispatcher;

    public function __construct(
        EventDispatcher $eventDispatcher
    ) {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function validate(
        string $fqcnOrigin,
        array $fqcnDestinations,
        array $astMap,
        bool $inverse = false //ignored
    ): void {
        /** @var AstNode $node */
        foreach ($astMap as $node) {
            if ($node->getClassName() !== $fqcnOrigin) {
                continue;
            }

            $parent = $this->getParent($node);
            $success = $parent === null || in_array($parent, $fqcnDestinations);
            $this->dispatchResult($success, $fqcnOrigin, $parent);

            return;
        }
    }

    private function getParent(AstNode $node): ?string
    {
        foreach ($node->getRelations() as $relation) {
            if ($relation instanceof Inheritance) {
                return $relation->relatedClass->getFQCN();
            }
        }

        return null;
    }

    private function dispatchResult(bool $result, string $fqcnOrigin, string $fqcnDestination = ''): void
    {
        $message = $result
            ? $fqcnOrigin . ' does not extend non-selected classes'
            : $fqcnOrigin . ' extends ' . $fqcnDestination;
        $event = $result ? StatementValidEvent::class : StatementNotValidEvent::class;

        $this->eventDispatcher->dispatch(new $event($message));
    }
}
