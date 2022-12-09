<?php

declare(strict_types=1);

namespace PHPat\Statement\Builder\Relation;

use PHPat\Rule\Assertion\Relation\ShouldNotExtend\ShouldNotExtend;
use PHPat\Statement\Builder\RelationStatementBuilder;

class ShouldNotExtendStatementBuilder extends RelationStatementBuilder
{
    /**
     * @return class-string<ShouldNotExtend>
     */
    protected function getAssertionClassname(): string
    {
        return ShouldNotExtend::class;
    }
}
