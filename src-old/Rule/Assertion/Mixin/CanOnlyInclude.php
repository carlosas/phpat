<?php

declare(strict_types=1);

namespace PHPatOld\Rule\Assertion\Mixin;

use PHPatOld\Parser\Ast\ClassLike;
use PHPatOld\Parser\Ast\ReferenceMap;
use PHPatOld\Rule\Assertion\AbstractAssertion;
use PHPatOld\Statement\Event\StatementNotValidEvent;
use PHPatOld\Statement\Event\StatementValidEvent;

class CanOnlyInclude extends AbstractAssertion
{
    public function acceptsRegex(): bool
    {
        return true;
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
            $traits  = $this->getTraits($node, $map);
            $success = true;
            foreach ($traits as $trait) {
                $result = $this->relationMatchesDestinations($trait, $included, $excluded);
                if (!$result->matched()) {
                    $success = false;
                    $this->dispatchResult(false, $node->getClassName(), $trait);
                }
            }

            if ($success) {
                $this->dispatchResult(true, $node->getClassName());
            }
        }
    }

    private function dispatchResult(bool $result, string $fqcnOrigin, string $fqcnDestination = ''): void
    {
        $message = $result
            ? $fqcnOrigin . ' does not include forbidden traits'
            : $fqcnOrigin . ' includes ' . $fqcnDestination;
        $event = $result ? StatementValidEvent::class : StatementNotValidEvent::class;

        $this->eventDispatcher->dispatch(new $event($message));
    }
}
