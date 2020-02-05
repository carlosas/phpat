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

class MustImplement implements Assertion
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
        bool $inverse = false
    ): void {
        $matchingNodes = $this->filterMatchingNodes($origin, $astMap);

        foreach ($matchingNodes as $node) {
            $interfaces = $this->getInterfaces($node);
            foreach ($interfaces as $interface) {
                foreach ($destinations as $destination) {
                    $this->dispatchResult($destination->matches($interface), $inverse, $origin->toString(), $interface);
                }
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
