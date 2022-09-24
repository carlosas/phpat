<?php

declare(strict_types=1);

namespace PHPat\Statement\Builder;

use PHPat\Rule\Assertion\Relation\ShouldImplement\ShouldImplement;

class ShouldImplementStatementBuilder extends StatementBuilder
{
    /**
     * @return class-string<\PHPat\Rule\Assertion\Relation\ShouldImplement\ShouldImplement>
     */
    protected function getAssertionClassname(): string
    {
        return ShouldImplement::class;
    }
}
