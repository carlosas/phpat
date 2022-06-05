<?php

declare(strict_types=1);

namespace PHPatOld\Rule\Assertion\Dependency;

use PHPatOld\Parser\Ast\ClassLike;
use PHPatOld\Parser\Ast\ReferenceMap;
use PHPatOld\Rule\Assertion\AbstractAssertion;
use PHPatOld\Statement\Event\StatementNotValidEvent;
use PHPatOld\Statement\Event\StatementValidEvent;

class CanOnlyDepend extends AbstractAssertion
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
            $dependencies = $this->getDependencies($node, $map);
            $success      = true;
            foreach ($dependencies as $dependency) {
                $result = $this->relationMatchesDestinations($dependency, $included, $excluded);
                if (!$result->matched()) {
                    $success = false;
                    $this->dispatchResult(false, $node->getClassName(), $dependency);
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
            ? $fqcnOrigin . ' does not depend on forbidden classes'
            : $fqcnOrigin . ' depends on ' . $fqcnDestination;
        $event = $result ? StatementValidEvent::class : StatementNotValidEvent::class;

        $this->eventDispatcher->dispatch(new $event($message));
    }
}
