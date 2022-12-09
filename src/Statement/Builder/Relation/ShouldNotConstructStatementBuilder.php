<?php

declare(strict_types=1);

namespace PHPat\Statement\Builder\Relation;

use PHPat\Rule\Assertion\Relation\ShouldNotConstruct\ShouldNotConstruct;
use PHPat\Statement\Builder\RelationStatementBuilder;

class ShouldNotConstructStatementBuilder extends RelationStatementBuilder
{
    /**
     * @return class-string<ShouldNotConstruct>
     */
    protected function getAssertionClassname(): string
    {
        return ShouldNotConstruct::class;
    }
}
