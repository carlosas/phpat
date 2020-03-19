<?php

declare(strict_types=1);

namespace PhpAT\Rule\Assertion\Composition;

use PhpAT\Statement\Event\StatementNotValidEvent;
use PhpAT\Statement\Event\StatementValidEvent;

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
