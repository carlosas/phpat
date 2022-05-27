<?php

namespace PHPat\Statement\Builder;

use PHPat\Rule\Assertion\ShouldImplement\ShouldImplement;

class ShouldImplementStatementBuilder extends StatementBuilder
{
    protected function getRuleClassname(): string
    {
        return ShouldImplement::class;
    }
}
