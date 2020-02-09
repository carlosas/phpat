<?php

declare(strict_types=1);

namespace PhpAT\Rule\Assertion\Mixin;

use PHPAT\EventDispatcher\EventDispatcher;
use PhpAT\Parser\AstNode;
use PhpAT\Parser\ClassLike;
use PhpAT\Parser\Relation\Mixin;
use PhpAT\Rule\Assertion\Assertion;
use PhpAT\Statement\Event\StatementNotValidEvent;
use PhpAT\Statement\Event\StatementValidEvent;

class MustOnlyInclude implements Assertion
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
            $mixins = $this->getMixins($node);
            $destinationsNotMatched = $destinations;
            foreach ($mixins as $mkey => $mixin) {
                foreach ($destinations as $dkey => $destination) {
                    if ($destination->matches($mixin)) {
                        $this->dispatchResult(true, true, $origin->toString(), $mixin);
                        unset($mixins[$mkey]);
                        unset($destinationsNotMatched[$dkey]);
                    }
                }
            }

            foreach ($mixins as $mixin) {
                $this->dispatchResult(true, false, $origin->toString(), $mixin);
            }

            foreach ($destinationsNotMatched as $destination) {
                $this->dispatchResult(false, true, $origin->toString(), $destination->toString());
            }
        }

        return;
    }

    private function getMixins(AstNode $node): array
    {
        foreach ($node->getRelations() as $relation) {
            if ($relation instanceof Mixin) {
                $mixins[] = $relation->relatedClass->getFQCN();
            }
        }

        return $mixins ?? [];
    }

    private function dispatchResult(bool $result, bool $should, string $fqcnOrigin, string $fqcnDestination): void
    {
        $action = $result ? ' includes ' : ' does not include ';
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
