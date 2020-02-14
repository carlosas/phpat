<?php

declare(strict_types=1);

namespace PhpAT\Rule\Assertion\Inheritance;

use PHPAT\EventDispatcher\EventDispatcher;
use PhpAT\Parser\ClassLike;
use PhpAT\Rule\Assertion\AbstractAssertion;
use PhpAT\Statement\Event\StatementNotValidEvent;
use PhpAT\Statement\Event\StatementValidEvent;

class MustNotExtend extends AbstractAssertion
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
            $parent = $this->getParent($node);
            foreach ($destinations as $destination) {
                if ($parent === null) {
                    $this->dispatchResult(false, $node->getClassName(), $destination->toString());
                    continue;
                }

                $this->dispatchResult(
                    $destination->matches($parent),
                    $node->getClassName(),
                    $destination->toString()
                );
            }
        }
    }

    private function dispatchResult(bool $extends, string $fqcnOrigin, string $fqcnDestination): void
    {
        $action = $extends ? ' extends ' : ' does not extend ';
        $event = $extends ? StatementNotValidEvent::class : StatementValidEvent::class;
        $message = $fqcnOrigin . $action . $fqcnDestination;

        $this->eventDispatcher->dispatch(new $event($message));
    }
}
