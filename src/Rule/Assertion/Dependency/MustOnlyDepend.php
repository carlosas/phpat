<?php

declare(strict_types=1);

namespace PhpAT\Rule\Assertion\Dependency;

use PHPAT\EventDispatcher\EventDispatcher;
use PhpAT\Parser\AstNode;
use PhpAT\Rule\Assertion\Assertion;
use PhpAT\Statement\Event\StatementNotValidEvent;
use PhpAT\Statement\Event\StatementValidEvent;

class MustOnlyDepend implements Assertion
{
    private $eventDispatcher;

    public function __construct(
        EventDispatcher $eventDispatcher
    ) {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function validate(
        string $fqcnOrigin,
        array $fqcnDestinations,
        array $astMap,
        bool $inverse = false //ignored
    ): void {
        /** @var AstNode $node */
        foreach ($astMap as $node) {
            if ($node->getClassName() !== $fqcnOrigin) {
                continue;
            }

            $dependencies = $node->getDependencies();
            foreach ($fqcnDestinations as $fqcnDestination) {
                $result = array_search($fqcnDestination, $dependencies, true);

                if ($result === false) {
                    $this->dispatchSelectedResult(false, $fqcnOrigin, $fqcnDestination);
                    continue;
                }
                $this->dispatchSelectedResult(true, $fqcnOrigin, $fqcnDestination);
                unset($dependencies[$result]);
            }

            if (empty($dependencies)) {
                $this->dispatchOthersResult(true, $fqcnOrigin);

                return;
            }

            foreach ($dependencies as $dependency) {
                $this->dispatchOthersResult(false, $fqcnOrigin, $dependency);
            }
        }

        return;
    }

    private function dispatchSelectedResult(bool $result, string $fqcnOrigin, string $fqcnDestination): void
    {
        $action = $result ? ' depends on ' : ' does not depend on ';
        $event = $result ? StatementValidEvent::class : StatementNotValidEvent::class;
        $message = $fqcnOrigin . $action . $fqcnDestination;

        $this->eventDispatcher->dispatch(new $event($message));
    }

    private function dispatchOthersResult(bool $result, string $fqcnOrigin, string $fqcnDestination = ''): void
    {
        $message = $result
            ? $fqcnOrigin . ' does not depend on non-selected classes'
            : $fqcnOrigin . ' depends ' . $fqcnDestination;
        $event = $result ? StatementValidEvent::class : StatementNotValidEvent::class;

        $this->eventDispatcher->dispatch(new $event($message));
    }
}
