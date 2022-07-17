<?php

declare(strict_types=1);

namespace PHPat\Statement\Builder;

use PHPat\Rule\Assertion\Relation\ShouldNotDepend\ShouldNotDepend;

class ShouldNotDependStatementBuilder extends StatementBuilder
{
    /**
     * @return class-string<\PHPat\Rule\Assertion\Relation\ShouldNotDepend\ShouldNotDepend>
     */
    protected function getAssertionClassname(): string
    {
        return ShouldNotDepend::class;
    }
}
