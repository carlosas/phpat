<?php

declare(strict_types=1);

namespace PhpAT\Rule\Assertion\Inheritance;

use PHPAT\EventDispatcher\EventDispatcher;
use PhpAT\Parser\ClassLike;
use PhpAT\Rule\Assertion\AbstractAssertion;
use PhpAT\Statement\Event\StatementNotValidEvent;
use PhpAT\Statement\Event\StatementValidEvent;

class MustExtend extends AbstractAssertion
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
            $parent = $this->getParent($node);
            foreach ($included as $destination) {
                $result = $this->destinationMatchesRelations($destination, $excluded, [$parent]);
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

    protected function dispatchResult(bool $result, string $fqcnOrigin, string $fqcnDestination): void
    {
        $action = $result ? ' extends ' : ' does not extend ';
        $event = $result ? StatementValidEvent::class : StatementNotValidEvent::class;
        $message = $fqcnOrigin . $action . $fqcnDestination;

        $this->eventDispatcher->dispatch(new $event($message));
    }
}
