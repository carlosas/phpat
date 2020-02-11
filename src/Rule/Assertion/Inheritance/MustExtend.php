<?php

declare(strict_types=1);

namespace PhpAT\Rule\Assertion\Inheritance;

use PHPAT\EventDispatcher\EventDispatcher;
use PhpAT\Parser\AstNode;
use PhpAT\Parser\ClassLike;
use PhpAT\Parser\Relation\Inheritance;
use PhpAT\Rule\Assertion\Assertion;
use PhpAT\Statement\Event\StatementNotValidEvent;
use PhpAT\Statement\Event\StatementValidEvent;

class MustExtend implements Assertion
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
            $parent = $this->getParent($node);
            foreach ($destinations as $destination) {
                $matches = ($parent !== null && $destination->matches($parent));
                $this->dispatchResult(
                    $matches,
                    $inverse,
                    $origin->toString(),
                    $destination->toString()
                );
            }
        }

        return;
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

    private function dispatchResult(bool $result, bool $inverse, string $fqcnOrigin, string $fqcnDestination): void
    {
        $action = $result ? ' extends ' : ' does not extend ';
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
