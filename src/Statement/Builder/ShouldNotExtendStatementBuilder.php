<?php

namespace PHPat\Statement\Builder;

use PHPat\Rule\Assertion\ShouldNotExtend\ShouldNotExtend;

class ShouldNotExtendStatementBuilder extends StatementBuilder
{
    protected function getRuleClassname(): string
    {
        return ShouldNotExtend::class;
    }
}
