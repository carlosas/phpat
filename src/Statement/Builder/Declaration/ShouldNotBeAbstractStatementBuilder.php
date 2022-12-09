<?php

declare(strict_types=1);

namespace PHPat\Statement\Builder\Declaration;

use PHPat\Rule\Assertion\Declaration\ShouldBeAbstract\ShouldBeAbstract;
use PHPat\Rule\Assertion\Declaration\ShouldNotBeAbstract\ShouldNotBeAbstract;
use PHPat\Statement\Builder\DeclarationStatementBuilder;

class ShouldNotBeAbstractStatementBuilder extends DeclarationStatementBuilder
{
    /**
     * @return class-string<ShouldNotBeAbstract>
     */
    protected function getAssertionClassname(): string
    {
        return ShouldNotBeAbstract::class;
    }
}
