<?php

declare(strict_types=1);

namespace PhpAT\Rule\Type\Mixin;

use PHPAT\EventDispatcher\EventDispatcher;
use PhpAT\Parser\AstNode;
use PhpAT\Rule\Type\RuleType;
use PhpAT\Statement\Event\StatementNotValidEvent;
use PhpAT\Statement\Event\StatementValidEvent;

class CanOnlyInclude implements RuleType
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

            $mixins = $node->getMixins();
            foreach ($mixins as $key => $value) {
                if (in_array($value, $fqcnDestinations)) {
                    unset($mixins[$key]);
                }
            }

            if (empty($mixins)) {
                $this->dispatchResult(true, $fqcnOrigin);

                return;
            }

            foreach ($mixins as $mixin) {
                $this->dispatchResult(false, $fqcnOrigin, $mixin);
            }
        }

        return;
    }

    private function dispatchResult(bool $result, string $fqcnOrigin, string $fqcnDestination = ''): void
    {
        $message = $result
            ? $fqcnOrigin . ' does not include non-selected traits'
            : $fqcnOrigin . ' includes ' . $fqcnDestination;
        $event = $result ? StatementValidEvent::class : StatementNotValidEvent::class;

        $this->eventDispatcher->dispatch(new $event($message));
    }
}
