<?php

declare(strict_types=1);

namespace PhpAT\Rule\Assertion\Mixin;

use PHPAT\EventDispatcher\EventDispatcher;
use PhpAT\Parser\ClassLike;
use PhpAT\Rule\Assertion\AbstractAssertion;
use PhpAT\Statement\Event\StatementNotValidEvent;
use PhpAT\Statement\Event\StatementValidEvent;

class MustOnlyInclude extends AbstractAssertion
{
    public function __construct(
        EventDispatcher $eventDispatcher
    ) {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function acceptsRegex(): bool
    {
        return false;
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
            $mixins = $this->getTraits($node);
            $destinationsNotMatched = $destinations;

            foreach ($mixins as $key => $mixin) {
                foreach ($destinations as $dkey => $destination) {
                    if ($destination->matches($mixin)) {
                        $this->dispatchResult(true, $node->getClassName(), $mixin);
                        unset($mixins[$key]);
                        unset($destinationsNotMatched[$dkey]);
                        break;
                    }
                }
            }

            foreach ($destinationsNotMatched as $notMatched) {
                $this->dispatchResult(false, $node->getClassName(), $notMatched->toString());
            }

            if (empty($mixins)) {
                $this->dispatchOthersResult(false, $node->getClassName());
            }
            foreach ($mixins as $mixin) {
                $this->dispatchOthersResult(true, $node->getClassName(), $mixin);
            }
        }
    }

    private function dispatchResult(bool $includes, string $fqcnOrigin, string $fqcnDestination): void
    {
        $action = $includes ? ' includes ' : ' does not include ';
        $event = $includes ? StatementValidEvent::class : StatementNotValidEvent::class;
        $message = $fqcnOrigin . $action . $fqcnDestination;

        $this->eventDispatcher->dispatch(new $event($message));
    }

    private function dispatchOthersResult(bool $includes, string $fqcnOrigin, string $fqcnDestination = ''): void
    {
        $message = $includes ?
            $fqcnOrigin . ' includes ' . $fqcnDestination
            : $fqcnOrigin . ' does not include non-selected classes';
        $event = $includes ? StatementNotValidEvent::class : StatementValidEvent::class;

        $this->eventDispatcher->dispatch(new $event($message));
    }
}
