<?php

declare(strict_types=1);

namespace PhpAT\Rule\Assertion\Dependency;

use PhpAT\App\Configuration;
use PHPAT\EventDispatcher\EventDispatcher;
use PhpAT\Parser\Ast\ReferenceMap;
use PhpAT\Parser\Ast\ClassLike;
use PhpAT\Rule\Assertion\AbstractAssertion;
use PhpAT\Statement\Event\StatementNotValidEvent;
use PhpAT\Statement\Event\StatementValidEvent;

class CanOnlyDepend extends AbstractAssertion
{
    public function acceptsRegex(): bool
    {
        return true;
    }

    /**
     * @param ClassLike    $origin
     * @param ClassLike[]  $included
     * @param ClassLike[]  $excluded
     * @param ReferenceMap $map
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
            $success = true;
            foreach ($dependencies as $key => $dependency) {
                $result = $this->relationMatchesDestinations($dependency, $included, $excluded);
                if ($result->matched() === false) {
                    $success = false;
                    $this->dispatchResult(false, $node->getClassName(), $dependency);
                }
            }

            if ($success === true) {
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
