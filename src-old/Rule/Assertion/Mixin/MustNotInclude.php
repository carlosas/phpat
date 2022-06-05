<?php

declare(strict_types=1);

namespace PHPatOld\Rule\Assertion\Mixin;

use PHPatOld\Statement\Event\StatementNotValidEvent;
use PHPatOld\Statement\Event\StatementValidEvent;

class MustNotInclude extends MustInclude
{
    public function acceptsRegex(): bool
    {
        return true;
    }

    protected function getEventClassName(bool $includes): string
    {
        return $includes ? StatementNotValidEvent::class : StatementValidEvent::class;
    }
}
