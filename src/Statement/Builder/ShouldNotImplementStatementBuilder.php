<?php

namespace PHPat\Statement\Builder;

use PHPat\Rule\Assertion\ShouldNotImplement\ShouldNotImplement;

class ShouldNotImplementStatementBuilder extends StatementBuilder
{
    protected function getAssertionClassname(): string
    {
        return ShouldNotImplement::class;
    }
}
