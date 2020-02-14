<?php

declare(strict_types=1);

namespace PhpAT\Rule\Assertion\Composition;

use PHPAT\EventDispatcher\EventDispatcher;
use PhpAT\Parser\ClassLike;
use PhpAT\Rule\Assertion\AbstractAssertion;
use PhpAT\Statement\Event\StatementNotValidEvent;
use PhpAT\Statement\Event\StatementValidEvent;

class MustOnlyImplement extends AbstractAssertion
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
            $interfaces = $this->getInterfaces($node);
            $destinationsNotMatched = $destinations;

            foreach ($interfaces as $key => $interface) {
                foreach ($destinations as $dkey => $destination) {
                    if ($destination->matches($interface)) {
                        $this->dispatchResult(true, $node->getClassName(), $interface);
                        unset($interfaces[$key]);
                        unset($destinationsNotMatched[$dkey]);
                        break;
                    }
                }
            }

            foreach ($destinationsNotMatched as $notMatched) {
                $this->dispatchResult(false, $node->getClassName(), $notMatched->toString());
            }

            if (empty($interfaces)) {
                $this->dispatchOthersResult(false, $node->getClassName());
            }
            foreach ($interfaces as $interface) {
                $this->dispatchOthersResult(true, $node->getClassName(), $interface);
            }
        }
    }

    private function dispatchResult(bool $implements, string $fqcnOrigin, string $fqcnDestination): void
    {
        $action = $implements ? ' implements ' : ' does not implement ';
        $event = $implements ? StatementValidEvent::class : StatementNotValidEvent::class;
        $message = $fqcnOrigin . $action . $fqcnDestination;

        $this->eventDispatcher->dispatch(new $event($message));
    }

    private function dispatchOthersResult(bool $implements, string $fqcnOrigin, string $fqcnDestination = ''): void
    {
        $message = $implements ?
            $fqcnOrigin . ' implements ' . $fqcnDestination
            : $fqcnOrigin . ' does not implement non-selected classes';
        $event = $implements ? StatementNotValidEvent::class : StatementValidEvent::class;

        $this->eventDispatcher->dispatch(new $event($message));
    }
}
