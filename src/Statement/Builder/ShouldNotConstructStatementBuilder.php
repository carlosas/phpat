<?php

namespace PHPat\Statement\Builder;

use PHPat\Rule\Assertion\ShouldNotConstruct\ShouldNotConstruct;

class ShouldNotConstructStatementBuilder extends StatementBuilder
{
    protected function getRuleClassname(): string
    {
        return ShouldNotConstruct::class;
    }
}
