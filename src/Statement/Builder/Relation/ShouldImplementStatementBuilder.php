<?php

declare(strict_types=1);

namespace PHPat\Statement\Builder\Relation;

use PHPat\Rule\Assertion\Relation\ShouldImplement\ShouldImplement;
use PHPat\Statement\Builder\RelationStatementBuilder;

class ShouldImplementStatementBuilder extends RelationStatementBuilder
{
    /**
     * @return class-string<\PHPat\Rule\Assertion\Relation\ShouldImplement\ShouldImplement>
     */
    protected function getAssertionClassname(): string
    {
        return ShouldImplement::class;
    }
}
