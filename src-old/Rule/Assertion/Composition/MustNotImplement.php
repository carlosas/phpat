<?php

declare(strict_types=1);

namespace PHPatOld\Rule\Assertion\Composition;

use PHPatOld\Statement\Event\StatementNotValidEvent;
use PHPatOld\Statement\Event\StatementValidEvent;

class MustNotImplement extends MustImplement
{
    public function acceptsRegex(): bool
    {
        return true;
    }

    protected function getEventClassName(bool $implements): string
    {
        return $implements ? StatementNotValidEvent::class : StatementValidEvent::class;
    }
}
