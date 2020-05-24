<?php

declare(strict_types=1);

namespace PhpAT\Rule\Assertion\Mixin;

use PHPAT\EventDispatcher\EventDispatcher;
use PhpAT\Parser\Ast\ReferenceMap;
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
     * @param ClassLike    $origin
     * @param ClassLike[]  $included
     * @param ClassLike[]  $excluded
     * @param ReferenceMap $map
     */
    public function validate(
        ClassLike $origin,
        array $included,
        array $excluded,
        ReferenceMap $map
    ): void {
        $matchingNodes = $this->filterMatchingNodes($origin, $map);

        foreach ($matchingNodes as $node) {
            $traits = $this->getTraits($node, $map);
            foreach ($included as $destination) {
                $result = $this->destinationMatchesRelations($destination, $excluded, $traits);
                if ($result->matched() === true) {
                    foreach ($result->getMatches() as $match) {
                        $this->dispatchResult(true, $node->getClassName(), $match);
                    }
                } else {
                    $this->dispatchResult(false, $node->getClassName(), $destination->toString());
                }
            }
            $success = true;
            foreach ($traits as $trait) {
                $result = $this->relationMatchesDestinations($trait, $included, $excluded);
                if ($result->matched() === false) {
                    $success = false;
                    $this->dispatchOthersResult(true, $node->getClassName(), $trait);
                }
            }
            if ($success === true) {
                $this->dispatchOthersResult(false, $node->getClassName());
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
            : $fqcnOrigin . ' does not include forbidden classes';
        $event = $includes ? StatementNotValidEvent::class : StatementValidEvent::class;

        $this->eventDispatcher->dispatch(new $event($message));
    }
}
