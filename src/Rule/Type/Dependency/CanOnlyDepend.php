<?php

declare(strict_types=1);

namespace PhpAT\Rule\Type\Dependency;

use PHPAT\EventDispatcher\EventDispatcher;
use PhpAT\Parser\AstNode;
use PhpAT\Rule\Type\RuleType;
use PhpAT\Statement\Event\StatementNotValidEvent;
use PhpAT\Statement\Event\StatementValidEvent;

class CanOnlyDepend implements RuleType
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
            if ($node->getClassName() === $fqcnOrigin) {
                $dependencies = $node->getDependencies();
                foreach ($dependencies as $key => $value) {
                    if (in_array($value, $fqcnDestinations)) {
                        unset($dependencies[$key]);
                    }
                }

                if (empty($dependencies)) {
                    $this->dispatchResult(true, $fqcnOrigin);

                    return;
                }

                foreach ($dependencies as $dependency) {
                    $this->dispatchResult(false, $fqcnOrigin, $dependency);
                }
            }
        }

        return;
    }

    private function dispatchResult(bool $result, string $fqcnOrigin, string $fqcnDestination = ''): void
    {
        $message = $result
            ? $fqcnOrigin . ' does not depend on non-selected classes'
            : $fqcnOrigin . ' depends on ' . $fqcnDestination;
        $event = $result ? StatementValidEvent::class : StatementNotValidEvent::class;

        $this->eventDispatcher->dispatch(new $event($message));
    }
}
