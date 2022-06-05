<?php

declare(strict_types=1);

namespace PHPatOld\Rule\Assertion\Composition;

use PHPatOld\Parser\Ast\ClassLike;
use PHPatOld\Parser\Ast\ReferenceMap;
use PHPatOld\Rule\Assertion\AbstractAssertion;
use PHPatOld\Statement\Event\StatementNotValidEvent;
use PHPatOld\Statement\Event\StatementValidEvent;

class MustOnlyImplement extends AbstractAssertion
{
    public function acceptsRegex(): bool
    {
        return false;
    }

    /**
     * @param array<ClassLike> $included
     * @param array<ClassLike> $excluded
     */
    public function validate(
        ClassLike $origin,
        array $included,
        array $excluded,
        ReferenceMap $map
    ): void {
        $matchingNodes = $this->filterMatchingNodes($origin, $map);

        foreach ($matchingNodes as $node) {
            $interfaces = $this->getInterfaces($node, $map);
            foreach ($included as $destination) {
                $result = $this->destinationMatchesRelations($destination, $excluded, $interfaces);
                if ($result->matched()) {
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
                if (!$result->matched()) {
                    $success = false;
                    $this->dispatchOthersResult(true, $node->getClassName(), $interface);
                }
            }
            if ($success) {
                $this->dispatchOthersResult(false, $node->getClassName());
            }
        }
    }

    private function dispatchResult(bool $implements, string $fqcnOrigin, string $fqcnDestination): void
    {
        $action  = $implements ? ' implements ' : ' does not implement ';
        $event   = $implements ? StatementValidEvent::class : StatementNotValidEvent::class;
        $message = $fqcnOrigin . $action . $fqcnDestination;

        $this->eventDispatcher->dispatch(new $event($message));
    }

    private function dispatchOthersResult(bool $implements, string $fqcnOrigin, string $fqcnDestination = ''): void
    {
        $message = $implements ?
            $fqcnOrigin . ' implements ' . $fqcnDestination
            : $fqcnOrigin . ' does not implement forbidden classes';
        $event = $implements ? StatementNotValidEvent::class : StatementValidEvent::class;

        $this->eventDispatcher->dispatch(new $event($message));
    }
}
