<?php

namespace PHPat\Statement\Builder;

use PHPat\Rule\Assertion\ShouldNotImplement\ShouldNotImplement;

class ShouldNotImplementStatementBuilder extends StatementBuilder
{
    protected function getRuleClassname(): string
    {
        return ShouldNotImplement::class;
    }
}
