<?php

declare(strict_types=1);

namespace PHPat\Statement\Builder;

use PHPat\Rule\Assertion\ShouldImplement\ShouldImplement;

class ShouldImplementStatementBuilder extends StatementBuilder
{
    protected function getAssertionClassname(): string
    {
        return ShouldImplement::class;
    }
}
