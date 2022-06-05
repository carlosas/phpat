<?php

declare(strict_types=1);

namespace PHPatOld\Rule\Assertion\Dependency;

use PHPatOld\Parser\Ast\ClassLike;
use PHPatOld\Parser\Ast\ReferenceMap;
use PHPatOld\Rule\Assertion\AbstractAssertion;
use PHPatOld\Statement\Event\StatementNotValidEvent;
use PHPatOld\Statement\Event\StatementValidEvent;

class MustOnlyDepend extends AbstractAssertion
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
            $dependencies = $this->getDependencies($node, $map);
            foreach ($included as $destination) {
                $result = $this->destinationMatchesRelations($destination, $excluded, $dependencies);
                if ($result->matched()) {
                    foreach ($result->getMatches() as $match) {
                        $this->dispatchResult(true, $node->getClassName(), $match);
                    }
                } else {
                    $this->dispatchResult(false, $node->getClassName(), $destination->toString());
                }
            }
            $success = true;
            foreach ($dependencies as $dependency) {
                $result = $this->relationMatchesDestinations($dependency, $included, $excluded);
                if (!$result->matched()) {
                    $success = false;
                    $this->dispatchOthersResult(true, $node->getClassName(), $dependency);
                }
            }
            if ($success) {
                $this->dispatchOthersResult(false, $node->getClassName());
            }
        }
    }

    private function dispatchResult(bool $depends, string $fqcnOrigin, string $fqcnDestination): void
    {
        $action  = $depends ? ' depends on ' : ' does not depend on ';
        $event   = $depends ? StatementValidEvent::class : StatementNotValidEvent::class;
        $message = $fqcnOrigin . $action . $fqcnDestination;

        $this->eventDispatcher->dispatch(new $event($message));
    }

    private function dispatchOthersResult(bool $depends, string $fqcnOrigin, string $fqcnDestination = ''): void
    {
        $message = $depends ?
            $fqcnOrigin . ' depends on ' . $fqcnDestination
            : $fqcnOrigin . ' does not depend on forbidden classes';
        $event = $depends ? StatementNotValidEvent::class : StatementValidEvent::class;

        $this->eventDispatcher->dispatch(new $event($message));
    }
}
