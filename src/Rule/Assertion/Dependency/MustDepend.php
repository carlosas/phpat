<?php

declare(strict_types=1);

namespace PhpAT\Rule\Assertion\Dependency;

use PHPAT\EventDispatcher\EventDispatcher;
use PhpAT\Parser\AstNode;
use PhpAT\Parser\ClassLike;
use PhpAT\Parser\Relation\Dependency;
use PhpAT\Rule\Assertion\Assertion;
use PhpAT\Statement\Event\StatementNotValidEvent;
use PhpAT\Statement\Event\StatementValidEvent;

class MustDepend implements Assertion
{
    /**
     * @var EventDispatcher
     */
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
            $dependencies = $this->getDependencies($node);
            foreach ($destinations as $destination) {
                $matches = $this->matches($destination, $dependencies);
                $this->dispatchResult($matches ?? false, $inverse, $origin->toString(), $destination->toString());
            }
        }

        return;
    }

    private function getDependencies(AstNode $node): array
    {
        foreach ($node->getRelations() as $relation) {
            if ($relation instanceof Dependency) {
                $dependencies[] = $relation->relatedClass->getFQCN();
            }
        }

        return $dependencies ?? [];
    }

    private function matches(ClassLike $destination, array $dependencies): bool
    {
        foreach ($dependencies as $dependency) {
            if ($destination->matches($dependency)) {
                $matches = true;
            }
        }

        return $matches ?? false;
    }

    private function dispatchResult(bool $result, bool $inverse, string $fqcnOrigin, string $fqcnDestination): void
    {
        $action = $result ? ' depends on ' : ' does not depend on ';
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
