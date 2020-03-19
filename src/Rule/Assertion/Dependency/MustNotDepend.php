<?php

declare(strict_types=1);

namespace PhpAT\Rule\Assertion\Dependency;

use PhpAT\Statement\Event\StatementNotValidEvent;
use PhpAT\Statement\Event\StatementValidEvent;

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
