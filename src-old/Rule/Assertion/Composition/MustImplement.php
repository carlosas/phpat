<?php

declare(strict_types=1);

namespace PHPatOld\Rule\Assertion\Composition;

use PHPatOld\Parser\Ast\ClassLike;
use PHPatOld\Parser\Ast\ReferenceMap;
use PHPatOld\Rule\Assertion\AbstractAssertion;
use PHPatOld\Statement\Event\StatementNotValidEvent;
use PHPatOld\Statement\Event\StatementValidEvent;

class MustImplement extends AbstractAssertion
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
        }
    }

    protected function dispatchResult(bool $result, string $fqcnOrigin, string $fqcnDestination): void
    {
        $event   = $this->getEventClassName($result);
        $action  = $result ? ' implements ' : ' does not implement ';
        $message = $fqcnOrigin . $action . $fqcnDestination;

        $this->eventDispatcher->dispatch(new $event($message));
    }

    protected function getEventClassName(bool $implements): string
    {
        return $implements ? StatementValidEvent::class : StatementNotValidEvent::class;
    }
}
