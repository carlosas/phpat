<?php

declare(strict_types=1);

namespace PhpAT\Rule\Assertion\Inheritance;

use PhpAT\Statement\Event\StatementNotValidEvent;
use PhpAT\Statement\Event\StatementValidEvent;

class MustNotExtend extends MustExtend
{
    public function acceptsRegex(): bool
    {
        return true;
    }

    protected function dispatchResult(bool $result, string $fqcnOrigin, string $fqcnDestination): void
    {
        $action  = $result ? ' extends ' : ' does not extend ';
        $event   = $result ? StatementNotValidEvent::class : StatementValidEvent::class;
        $message = $fqcnOrigin . $action . $fqcnDestination;

        $this->eventDispatcher->dispatch(new $event($message));
    }
}
