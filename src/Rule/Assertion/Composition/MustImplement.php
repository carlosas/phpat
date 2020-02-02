<?php

declare(strict_types=1);

namespace PhpAT\Rule\Assertion\Composition;

use PHPAT\EventDispatcher\EventDispatcher;
use PhpAT\Parser\AstNode;
use PhpAT\Parser\Relation\Composition;
use PhpAT\Rule\Assertion\Assertion;
use PhpAT\Statement\Event\StatementNotValidEvent;
use PhpAT\Statement\Event\StatementValidEvent;

class MustImplement implements Assertion
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
        bool $inverse = false
    ): void {
        /** @var AstNode $node */
        foreach ($astMap as $node) {
            if ($node->getClassName() !== $fqcnOrigin) {
                continue;
            }

            $interfaces = $this->getInterfaces($node);
            foreach ($fqcnDestinations as $destination) {
                $result = in_array($destination, $interfaces);
                $this->dispatchResult($result, $inverse, $fqcnOrigin, $destination);
            }
        }

        return;
    }

    private function getInterfaces(AstNode $node): array
    {
        foreach ($node->getRelations() as $relation) {
            if ($relation instanceof Composition) {
                $interfaces[] = $relation->relatedClass->getFQCN();
            }
        }

        return $interfaces ?? [];
    }

    private function dispatchResult(bool $result, bool $inverse, string $fqcnOrigin, string $fqcnDestination): void
    {
        $action = $result ? ' implements ' : ' does not implement ';
        $event = ($result xor $inverse) ? StatementValidEvent::class : StatementNotValidEvent::class;
        $message = $fqcnOrigin . $action . $fqcnDestination;

        $this->eventDispatcher->dispatch(new $event($message));
    }
}
