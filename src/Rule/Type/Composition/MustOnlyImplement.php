<?php

declare(strict_types=1);

namespace PhpAT\Rule\Type\Composition;

use PHPAT\EventDispatcher\EventDispatcher;
use PhpAT\Parser\AstNode;
use PhpAT\Rule\Type\RuleType;
use PhpAT\Statement\Event\StatementNotValidEvent;
use PhpAT\Statement\Event\StatementValidEvent;

class MustOnlyImplement implements RuleType
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

            $implemented = $node->getInterfaces();
            foreach ($fqcnDestinations as $fqcnDestination) {
                $result = array_search($fqcnDestination, $implemented, true);

                if ($result === false) {
                    $this->dispatchSelectedResult(false, $fqcnOrigin, $fqcnDestination);
                    continue;
                }
                $this->dispatchSelectedResult(true, $fqcnOrigin, $fqcnDestination);
                unset($implemented[$result]);
            }

            if (empty($implemented)) {
                $this->dispatchOthersResult(true, $fqcnOrigin);

                return;
            }

            foreach ($implemented as $interface) {
                $this->dispatchOthersResult(false, $fqcnOrigin, $interface);
            }
        }

        return;
    }

    private function dispatchSelectedResult(bool $result, string $fqcnOrigin, string $fqcnDestination): void
    {
        $action = $result ? ' implements ' : ' does not implement ';
        $event = $result ? StatementValidEvent::class : StatementNotValidEvent::class;
        $message = $fqcnOrigin . $action . $fqcnDestination;

        $this->eventDispatcher->dispatch(new $event($message));
    }

    private function dispatchOthersResult(bool $result, string $fqcnOrigin, string $fqcnDestination = ''): void
    {
        $message = $result
            ? $fqcnOrigin . ' does not implement non-selected interfaces'
            : $fqcnOrigin . ' implements ' . $fqcnDestination;
        $event = $result ? StatementValidEvent::class : StatementNotValidEvent::class;

        $this->eventDispatcher->dispatch(new $event($message));
    }
}
