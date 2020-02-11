<?php

declare(strict_types=1);

namespace PhpAT\Rule\Assertion\Composition;

use PHPAT\EventDispatcher\EventDispatcher;
use PhpAT\Parser\AstNode;
use PhpAT\Parser\ClassLike;
use PhpAT\Parser\FullClassName;
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
     */
    public function validate(
        ClassLike $origin,
        array $destinations,
        array $astMap
    ): void {
        $matchingNodes = $this->filterMatchingNodes($origin, $astMap);

        foreach ($matchingNodes as $node) {
            $interfaces = $this->getInterfaces($node);
            foreach ($destinations as $destination) {
                if ($destination instanceof FullClassName) {
                    $matches = $this->matches($destination, $interfaces);
                    $this->dispatchResult($matches, $origin->toString(), $destination->toString());
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

    private function matches(ClassLike $destination, array $interfaces): bool
    {
        foreach ($interfaces as $interface) {
            if ($destination->matches($interface)) {
                $matches = true;
            }
        }

        return $matches ?? false;
    }

    private function dispatchResult(bool $result, string $fqcnOrigin, string $fqcnDestination): void
    {
        $action = $result ? ' implements ' : ' does not implement ';
        $event = $result ? StatementValidEvent::class : StatementNotValidEvent::class;
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
