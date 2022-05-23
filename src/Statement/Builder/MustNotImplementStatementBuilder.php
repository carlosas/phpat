<?php

namespace PHPat\Statement\Builder;

use PHPat\Rule\Assertion\MustNotImplement\MustNotImplement;

class MustNotImplementStatementBuilder extends StatementBuilder
{
    protected function getRuleClassname(): string
    {
        return MustNotImplement::class;
    }
}
