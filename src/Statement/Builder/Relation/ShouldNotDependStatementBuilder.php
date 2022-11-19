<?php

declare(strict_types=1);

namespace PHPat\Statement\Builder\Relation;

use PHPat\Rule\Assertion\Relation\ShouldNotDepend\ShouldNotDepend;
use PHPat\Statement\Builder\RelationStatementBuilder;

class ShouldNotDependStatementBuilder extends RelationStatementBuilder
{
    /**
     * @return class-string<\PHPat\Rule\Assertion\Relation\ShouldNotDepend\ShouldNotDepend>
     */
    protected function getAssertionClassname(): string
    {
        return ShouldNotDepend::class;
    }
}
