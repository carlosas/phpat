<?php

namespace PHPat\Statement\Builder;

use PHPat\Rule\Assertion\ShouldNotDepend\ShouldNotDepend;

class ShouldNotDependStatementBuilder extends StatementBuilder
{
    protected function getRuleClassname(): string
    {
        return ShouldNotDepend::class;
    }
}
