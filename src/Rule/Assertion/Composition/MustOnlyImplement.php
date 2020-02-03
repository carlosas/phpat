<?php

declare(strict_types=1);

namespace PhpAT\Rule\Assertion\Composition;

use PHPAT\EventDispatcher\EventDispatcher;
use PhpAT\Parser\AstNode;
use PhpAT\Parser\Relation\Composition;
use PhpAT\Rule\Assertion\Assertion;
use PhpAT\Statement\Event\StatementNotValidEvent;
use PhpAT\Statement\Event\StatementValidEvent;
use PhpParser\Node;

class MustOnlyImplement implements Assertion
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

            $interfaces = $this->getInterfaces($node);

            foreach ($fqcnDestinations as $fqcnDestination) {
                $result = array_search($fqcnDestination, $interfaces, true);

                if ($result !== false) {
                    $this->dispatchSelectedResult(true, $fqcnOrigin, $fqcnDestination);
                    unset($interfaces[$result]);
                    continue;
                }
                $this->dispatchSelectedResult(false, $fqcnOrigin, $fqcnDestination);
            }

            if (empty($interfaces)) {
                $this->dispatchOthersResult(true, $fqcnOrigin);

                return;
            }

            foreach ($interfaces as $interface) {
                $this->dispatchOthersResult(false, $fqcnOrigin, $interface);
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

    private function dispatchSelectedResult(bool $result, string $fqcnOrigin, string $fqcnDestination): void
    {
        $action = $result ? ' implements ' : ' does not implement ';
        $event = $result ? StatementValidEvent::class : StatementNotValidEvent::class;
        $message = $fqcnOrigin . $action . $fqcnDestination;

        $this->eventDispatcher->dispatch(new $event($message));
    }

    private function dispatchOthersResult(bool $result, string $fqcnOrigin, string $fqcnDestination = ''): void
    {
        $message = $result
            ? $fqcnOrigin . ' does not implement non-selected interfaces'
            : $fqcnOrigin . ' implements ' . $fqcnDestination;
        $event = $result ? StatementValidEvent::class : StatementNotValidEvent::class;

        $this->eventDispatcher->dispatch(new $event($message));
    }
}
