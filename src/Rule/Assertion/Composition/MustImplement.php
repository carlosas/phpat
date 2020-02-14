<?php

declare(strict_types=1);

namespace PhpAT\Rule\Assertion\Composition;

use PHPAT\EventDispatcher\EventDispatcher;
use PhpAT\Parser\ClassLike;
use PhpAT\Rule\Assertion\AbstractAssertion;
use PhpAT\Statement\Event\StatementNotValidEvent;
use PhpAT\Statement\Event\StatementValidEvent;

class MustImplement extends AbstractAssertion
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
            foreach ($destinations as $destination) {
                $matches = $this->matches($destination, $interfaces);
                $this->dispatchResult($matches, $node->getClassName(), $destination->toString());
            }
        }
    }

    private function matches(ClassLike $destination, array $interfaces): bool
    {
        foreach ($interfaces as $interface) {
            if ($destination->matches($interface)) {
                $matches = true;
            }
        }

        return $matches ?? false;
    }

    private function dispatchResult(bool $result, string $fqcnOrigin, string $fqcnDestination): void
    {
        $action = $result ? ' implements ' : ' does not implement ';
        $event = $result ? StatementValidEvent::class : StatementNotValidEvent::class;
        $message = $fqcnOrigin . $action . $fqcnDestination;

        $this->eventDispatcher->dispatch(new $event($message));
    }
}
