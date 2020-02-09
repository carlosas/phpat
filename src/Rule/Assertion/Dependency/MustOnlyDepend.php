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

class MustOnlyDepend implements Assertion
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
            $destinationsNotMatched = $destinations;

            foreach ($dependencies as $depkey => $dependency) {
                foreach ($destinations as $dkey => $destination) {
                    if ($destination->matches($dependency)) {
                        $this->dispatchResult(true, true, $origin->toString(), $dependency);
                        unset($dependencies[$depkey]);
                        unset($destinationsNotMatched[$dkey]);
                    }
                }
            }

            foreach ($dependencies as $dependency) {
                $this->dispatchResult(true, false, $origin->toString(), $dependency);
            }

            foreach ($destinationsNotMatched as $destination) {
                $this->dispatchResult(false, true, $origin->toString(), $destination->toString());
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

    private function dispatchResult(bool $result, bool $should, string $fqcnOrigin, string $fqcnDestination): void
    {
        $action = $result ? ' depends on ' : ' does not depend on ';
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
