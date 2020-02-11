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

class CanOnlyDepend implements Assertion
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
            $dependencies = $this->getDependencies($node);

            foreach ($dependencies as $key => $value) {
                foreach ($destinations as $destination) {
                    if ($destination->matches($value)) {
                        unset($dependencies[$key]);
                    }
                }
            }

            if (empty($dependencies)) {
                $this->dispatchResult(true, $origin->toString());

                return;
            }

            foreach ($dependencies as $dependency) {
                $this->dispatchResult(false, $origin->toString(), $dependency);
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

    private function dispatchResult(bool $result, string $fqcnOrigin, string $fqcnDestination = ''): void
    {
        $message = $result
            ? $fqcnOrigin . ' does not depend on non-selected classes'
            : $fqcnOrigin . ' depends on ' . $fqcnDestination;
        $event = $result ? StatementValidEvent::class : StatementNotValidEvent::class;

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
