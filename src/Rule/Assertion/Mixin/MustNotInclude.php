<?php

declare(strict_types=1);

namespace PhpAT\Rule\Assertion\Mixin;

use PHPAT\EventDispatcher\EventDispatcher;
use PhpAT\Parser\ClassLike;
use PhpAT\Rule\Assertion\AbstractAssertion;
use PhpAT\Statement\Event\StatementNotValidEvent;
use PhpAT\Statement\Event\StatementValidEvent;

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
