<?php

namespace PHPat\Statement\Builder;

use PHPat\Rule\Assertion\MustNotConstruct\MustNotConstruct;

class MustNotConstructStatementBuilder extends StatementBuilder
{
    protected function getRuleClassname(): string
    {
        return MustNotConstruct::class;
    }
}
