<?php

declare(strict_types=1);

namespace PhpAT\Rule\Assertion\Composition;

use PhpAT\Parser\Ast\ClassLike;
use PhpAT\Parser\Ast\ReferenceMap;
use PhpAT\Rule\Assertion\AbstractAssertion;
use PhpAT\Statement\Event\StatementNotValidEvent;
use PhpAT\Statement\Event\StatementValidEvent;

class CanOnlyImplement extends AbstractAssertion
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
            $interfaces = $this->getInterfaces($node, $map);
            $success    = true;
            foreach ($interfaces as $interface) {
                $result = $this->relationMatchesDestinations($interface, $included, $excluded);
                if (!$result->matched()) {
                    $success = false;
                    $this->dispatchResult(false, $node->getClassName(), $interface);
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
            ? $fqcnOrigin . ' does not implement forbidden interfaces'
            : $fqcnOrigin . ' implements ' . $fqcnDestination;
        $event = $result ? StatementValidEvent::class : StatementNotValidEvent::class;

        $this->eventDispatcher->dispatch(new $event($message));
    }
}
