<?php

declare(strict_types=1);

namespace PHPat\Statement\Builder\Relation;

use PHPat\Rule\Assertion\Relation\ShouldNotImplement\ShouldNotImplement;
use PHPat\Statement\Builder\RelationStatementBuilder;

class ShouldNotImplementStatementBuilder extends RelationStatementBuilder
{
    /**
     * @return class-string<ShouldNotImplement>
     */
    protected function getAssertionClassname(): string
    {
        return ShouldNotImplement::class;
    }
}
