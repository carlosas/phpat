<?php

declare(strict_types=1);

namespace PhpAT\Rule\Assertion\Mixin;

use PHPAT\EventDispatcher\EventDispatcher;
use PhpAT\Parser\Ast\ReferenceMap;
use PhpAT\Parser\ClassLike;
use PhpAT\Rule\Assertion\AbstractAssertion;
use PhpAT\Statement\Event\StatementNotValidEvent;
use PhpAT\Statement\Event\StatementValidEvent;

class MustInclude extends AbstractAssertion
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
        }
    }

    private function dispatchResult(bool $result, string $fqcnOrigin, string $fqcnDestination): void
    {
        $action = $result ? ' includes ' : ' does not include ';
        $event = $this->getEventClassName($result);
        $message = $fqcnOrigin . $action . $fqcnDestination;

        $this->eventDispatcher->dispatch(new $event($message));
    }

    protected function getEventClassName(bool $includes): string
    {
        return $includes ? StatementValidEvent::class : StatementNotValidEvent::class;
    }
}
