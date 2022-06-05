<?php

declare(strict_types=1);

namespace PHPatOld\Rule\Assertion\Dependency;

use PHPatOld\Statement\Event\StatementNotValidEvent;
use PHPatOld\Statement\Event\StatementValidEvent;

class MustNotDepend extends MustDepend
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
