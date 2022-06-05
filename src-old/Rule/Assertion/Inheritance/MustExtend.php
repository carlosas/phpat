<?php

declare(strict_types=1);

namespace PHPatOld\Rule\Assertion\Inheritance;

use PHPatOld\Parser\Ast\ClassLike;
use PHPatOld\Parser\Ast\ReferenceMap;
use PHPatOld\Rule\Assertion\AbstractAssertion;
use PHPatOld\Statement\Event\StatementNotValidEvent;
use PHPatOld\Statement\Event\StatementValidEvent;

class MustExtend extends AbstractAssertion
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
            $parent = $this->getParent($node, $map);
            foreach ($included as $destination) {
                if ($parent === null) {
                    $this->dispatchResult(false, $node->getClassName(), $destination->toString());
                    continue;
                }

                $result = $this->destinationMatchesRelations($destination, $excluded, [$parent]);
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
        $action  = $result ? ' extends ' : ' does not extend ';
        $event   = $result ? StatementValidEvent::class : StatementNotValidEvent::class;
        $message = $fqcnOrigin . $action . $fqcnDestination;

        $this->eventDispatcher->dispatch(new $event($message));
    }
}
