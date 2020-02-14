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
            $dependencies = $this->getDependencies($node);
            $destinationsNotMatched = $destinations;

            foreach ($dependencies as $key => $dependency) {
                foreach ($destinations as $dkey => $destination) {
                    if ($destination->matches($dependency)) {
                        $this->dispatchResult(true, $node->getClassName(), $dependency);
                        unset($dependencies[$key]);
                        unset($destinationsNotMatched[$dkey]);
                        break;
                    }
                }
            }

            foreach ($destinationsNotMatched as $notMatched) {
                $this->dispatchResult(false, $node->getClassName(), $notMatched->toString());
            }

            if (empty($dependencies)) {
                $this->dispatchOthersResult(false, $node->getClassName());
            }
            foreach ($dependencies as $dependency) {
                $this->dispatchOthersResult(true, $node->getClassName(), $dependency);
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
