<?php

declare(strict_types=1);

namespace PhpAT\Rule\Type\Composition;

use PHPAT\EventDispatcher\EventDispatcher;
use PhpAT\Parser\AstNode;
use PhpAT\Rule\Type\RuleType;
use PhpAT\Statement\Event\StatementNotValidEvent;
use PhpAT\Statement\Event\StatementValidEvent;

class CanOnlyImplement implements RuleType
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
                $implemented = $node->getInterfaces();
                foreach ($implemented as $key => $value) {
                    if (in_array($value, $fqcnDestinations)) {
                        unset($implemented[$key]);
                    }
                }

                if (empty($implemented)) {
                    $this->dispatchResult(true, $fqcnOrigin);

                    return;
                }

                foreach ($implemented as $interface) {
                    $this->dispatchResult(false, $fqcnOrigin, $interface);
                }
            }
        }

        return;
    }

    private function dispatchResult(bool $result, string $fqcnOrigin, string $fqcnDestination = ''): void
    {
        $message = $result
            ? $fqcnOrigin . ' does not implement non-selected interfaces'
            : $fqcnOrigin . ' implements ' . $fqcnDestination;
        $event = $result ? StatementValidEvent::class : StatementNotValidEvent::class;

        $this->eventDispatcher->dispatch(new $event($message));
    }
}
