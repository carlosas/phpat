<?php

declare(strict_types=1);

namespace PHPat\Statement\Builder\Declaration;

use PHPat\Rule\Assertion\Declaration\ShouldBeAbstract\ShouldBeAbstract;
use PHPat\Statement\Builder\DeclarationStatementBuilder;

class ShouldBeAbstractStatementBuilder extends DeclarationStatementBuilder
{
    /**
     * @return class-string<ShouldBeAbstract>
     */
    protected function getAssertionClassname(): string
    {
        return ShouldBeAbstract::class;
    }
}
