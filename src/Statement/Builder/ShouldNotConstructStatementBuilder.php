<?php

namespace PHPat\Statement\Builder;

use PHPat\Rule\Assertion\ShouldNotConstruct\ShouldNotConstruct;

class ShouldNotConstructStatementBuilder extends StatementBuilder
{
    protected function getAssertionClassname(): string
    {
        return ShouldNotConstruct::class;
    }
}
