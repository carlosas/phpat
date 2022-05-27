<?php

namespace PHPat\Statement\Builder;

use PHPat\Rule\Assertion\ShouldNotExtend\ShouldNotExtend;

class ShouldNotExtendStatementBuilder extends StatementBuilder
{
    protected function getAssertionClassname(): string
    {
        return ShouldNotExtend::class;
    }
}
