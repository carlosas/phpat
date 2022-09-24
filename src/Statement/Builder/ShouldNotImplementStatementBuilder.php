<?php

declare(strict_types=1);

namespace PHPat\Statement\Builder;

use PHPat\Rule\Assertion\Relation\ShouldNotImplement\ShouldNotImplement;

class ShouldNotImplementStatementBuilder extends StatementBuilder
{
    /**
     * @return class-string<ShouldNotImplement>
     */
    protected function getAssertionClassname(): string
    {
        return ShouldNotImplement::class;
    }
}
