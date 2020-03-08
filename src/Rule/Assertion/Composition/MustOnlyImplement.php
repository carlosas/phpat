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
     * @param ClassLike[] $included
     * @param ClassLike[] $excluded
     * @param array       $astMap
     */
    public function validate(
        ClassLike $origin,
        array $included,
        array $excluded,
        array $astMap
    ): void {
        $matchingNodes = $this->filterMatchingNodes($origin, $astMap);

        foreach ($matchingNodes as $node) {
            $interfaces = $this->getInterfaces($node);
            foreach ($included as $destination) {
                $result = $this->destinationMatchesRelations($destination, $excluded, $interfaces);
                if ($result->matched() === true) {
                    foreach ($result->getMatches() as $match) {
                        $this->dispatchResult(true, $node->getClassName(), $match);
                    }
                } else {
                    $this->dispatchResult(false, $node->getClassName(), $destination->toString());
                }
            }
            $success = true;
            foreach ($interfaces as $interface) {
                $result = $this->relationMatchesDestinations($interface, $included, $excluded);
                if ($result->matched() === false) {
                    $success = false;
                    $this->dispatchOthersResult(true, $node->getClassName(), $interface);
                }
            }
            if ($success === true) {
                $this->dispatchOthersResult(false, $node->getClassName());
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
