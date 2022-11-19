<?php

declare(strict_types=1);

namespace PHPat\Statement\Builder\Relation;

use PHPat\Rule\Assertion\Relation\ShouldExtend\ShouldExtend;
use PHPat\Statement\Builder\RelationStatementBuilder;

class ShouldExtendStatementBuilder extends RelationStatementBuilder
{
    /**
     * @return class-string<ShouldExtend>
     */
    protected function getAssertionClassname(): string
    {
        return ShouldExtend::class;
    }
}
