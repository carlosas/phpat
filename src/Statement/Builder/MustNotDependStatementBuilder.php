<?php

namespace PHPat\Statement\Builder;

use PHPat\Rule\Assertion\MustNotDepend\MustNotDepend;

class MustNotDependStatementBuilder extends StatementBuilder
{
    protected function getRuleClassname(): string
    {
        return MustNotDepend::class;
    }
}
