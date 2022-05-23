<?php

namespace PHPat\Statement\Builder;

use PHPat\Rule\Assertion\MustNotExtend\MustNotExtend;

class MustNotExtendStatementBuilder extends StatementBuilder
{
    protected function getRuleClassname(): string
    {
        return MustNotExtend::class;
    }
}
