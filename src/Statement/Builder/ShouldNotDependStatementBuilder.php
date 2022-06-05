<?php

declare(strict_types=1);

namespace PHPat\Statement\Builder;

use PHPat\Rule\Assertion\ShouldNotDepend\ShouldNotDepend;

class ShouldNotDependStatementBuilder extends StatementBuilder
{
    protected function getAssertionClassname(): string
    {
        return ShouldNotDepend::class;
    }
}
