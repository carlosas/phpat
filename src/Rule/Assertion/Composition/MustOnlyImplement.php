<?php

declare(strict_types=1);

namespace PhpAT\Rule\Assertion\Composition;

use PHPAT\EventDispatcher\EventDispatcher;
use PhpAT\Parser\AstNode;
use PhpAT\Parser\ClassLike;
use PhpAT\Parser\Relation\Composition;
use PhpAT\Rule\Assertion\Assertion;
use PhpAT\Statement\Event\StatementNotValidEvent;
use PhpAT\Statement\Event\StatementValidEvent;

class MustOnlyImplement implements Assertion
{
    private $eventDispatcher;

    public function __construct(
        EventDispatcher $eventDispatcher
    ) {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param ClassLike   $origin
     * @param ClassLike[] $destinations
     * @param array       $astMap
     * @param bool        $inverse
     */
    public function validate(
        ClassLike $origin,
        array $destinations,
        array $astMap,
        bool $inverse = false //ignored
    ): void {
        $matchingNodes = $this->filterMatchingNodes($origin, $astMap);

        foreach ($matchingNodes as $node) {
            $interfaces = $this->getInterfaces($node);
            $destinationsNotMatched = $destinations;
            foreach ($interfaces as $ikey => $interface) {
                foreach ($destinations as $dkey => $destination) {
                    if ($destination->matches($interface)) {
                        $this->dispatchResult(true, true, $origin->toString(), $interface);
                        unset($interfaces[$ikey]);
                        unset($destinationsNotMatched[$dkey]);
                    }
                }
            }

            foreach ($interfaces as $interface) {
                $this->dispatchResult(true, false, $origin->toString(), $interface);
            }

            foreach ($destinationsNotMatched as $destination) {
                $this->dispatchResult(false, true, $origin->toString(), $destination->toString());
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

    private function dispatchResult(bool $result, bool $should, string $fqcnOrigin, string $fqcnDestination): void
    {
        $action = $result ? ' implements ' : ' does not implement ';
        $event = ($result xor $should) ? StatementNotValidEvent::class : StatementValidEvent::class;
        $message = $fqcnOrigin . $action . $fqcnDestination;

        $this->eventDispatcher->dispatch(new $event($message));
    }

    private function filterMatchingNodes(ClassLike $origin, array $astMap)
    {
        /** @var AstNode $node */
        foreach ($astMap as $node) {
            if ($origin->matches($node->getClassName())) {
                $found[] = $node;
            }
        }

        return $found ?? [];
    }
}
