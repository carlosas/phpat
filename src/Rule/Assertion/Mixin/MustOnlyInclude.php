<?php

declare(strict_types=1);

namespace PhpAT\Rule\Assertion\Mixin;

use PHPAT\EventDispatcher\EventDispatcher;
use PhpAT\Parser\AstNode;
use PhpAT\Parser\ClassLike;
use PhpAT\Parser\FullClassName;
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
     */
    public function validate(
        ClassLike $origin,
        array $destinations,
        array $astMap
    ): void {
        $matchingNodes = $this->filterMatchingNodes($origin, $astMap);

        foreach ($matchingNodes as $node) {
            $mixins = $this->getMixins($node);

            foreach ($mixins as $key => $mixin) {
                foreach ($destinations as $destination) {
                    if ($destination instanceof FullClassName) {
                        if ($destination->matches($mixin)) {
                            $this->dispatchSelectedResult(true, $origin->toString(), $mixin);
                            unset($mixins[$key]);
                            continue;
                        }
                    }
                }
            }

            if (empty($mixins)) {
                $this->dispatchOthersResult(true, $origin->toString());

                return;
            }

            foreach ($mixins as $mixin) {
                $this->dispatchOthersResult(false, $origin->toString(), $mixin);
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

    private function dispatchSelectedResult(bool $result, string $fqcnOrigin, string $fqcnDestination): void
    {
        $action = $result ? ' includes ' : ' does not include ';
        $event = $result ? StatementValidEvent::class : StatementNotValidEvent::class;
        $message = $fqcnOrigin . $action . $fqcnDestination;

        $this->eventDispatcher->dispatch(new $event($message));
    }

    private function dispatchOthersResult(bool $result, string $fqcnOrigin, string $fqcnDestination = ''): void
    {
        $message = $result
            ? $fqcnOrigin . ' does not include non-selected traits'
            : $fqcnOrigin . ' includes ' . $fqcnDestination;
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
