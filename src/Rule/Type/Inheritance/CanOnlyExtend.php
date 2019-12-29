<?php

declare(strict_types=1);

namespace PhpAT\Rule\Type\Inheritance;

use PHPAT\EventDispatcher\EventDispatcher;
use PhpAT\Parser\AstNode;
use PhpAT\Rule\Type\RuleType;
use PhpAT\Statement\Event\StatementNotValidEvent;
use PhpAT\Statement\Event\StatementValidEvent;

class CanOnlyExtend implements RuleType
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
                $parent = $node->getParent();
                if (!in_array($parent, $fqcnDestinations)) {
                    $this->dispatchResult(false, $fqcnOrigin, $parent);

                    return;
                }

                $this->dispatchResult(true, $fqcnOrigin);
            }
        }

        return;
    }

    private function dispatchResult(bool $result, string $fqcnOrigin, string $fqcnDestination = ''): void
    {
        $message = $result
            ? $fqcnOrigin . ' does not extend non-selected classes'
            : $fqcnOrigin . ' extends ' . $fqcnDestination;
        $event = $result ? StatementValidEvent::class : StatementNotValidEvent::class;

        $this->eventDispatcher->dispatch(new $event($message));
    }
}
