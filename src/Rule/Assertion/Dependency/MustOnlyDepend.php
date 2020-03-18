<?php

declare(strict_types=1);

namespace PhpAT\Rule\Assertion\Dependency;

use PHPAT\EventDispatcher\EventDispatcher;
use PhpAT\Parser\ClassLike;
use PhpAT\Rule\Assertion\AbstractAssertion;
use PhpAT\Statement\Event\StatementNotValidEvent;
use PhpAT\Statement\Event\StatementValidEvent;

class MustOnlyDepend extends AbstractAssertion
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
            $dependencies = $this->getDependencies($node);
            foreach ($included as $destination) {
                $result = $this->destinationMatchesRelations($destination, $excluded, $dependencies);
                if ($result->matched() === true) {
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
                if ($result->matched() === false) {
                    $success = false;
                    $this->dispatchOthersResult(true, $node->getClassName(), $dependency);
                }
            }
            if ($success === true) {
                $this->dispatchOthersResult(false, $node->getClassName());
            }
        }
    }

    private function dispatchResult(bool $depends, string $fqcnOrigin, string $fqcnDestination): void
    {
        $action = $depends ? ' depends on ' : ' does not depend on ';
        $event = $depends ? StatementValidEvent::class : StatementNotValidEvent::class;
        $message = $fqcnOrigin . $action . $fqcnDestination;

        $this->eventDispatcher->dispatch(new $event($message));
    }

    private function dispatchOthersResult(bool $depends, string $fqcnOrigin, string $fqcnDestination = ''): void
    {
        $message = $depends ?
            $fqcnOrigin . ' depends on ' . $fqcnDestination
            : $fqcnOrigin . ' does not depend on non-selected classes';
        $event = $depends ? StatementNotValidEvent::class : StatementValidEvent::class;

        $this->eventDispatcher->dispatch(new $event($message));
    }
}
