<?php

declare(strict_types=1);

namespace PHPat\Statement\Builder\Declaration;

use PHPat\Rule\Assertion\Declaration\ShouldBeFinal\ShouldBeFinal;
use PHPat\Statement\Builder\DeclarationStatementBuilder;
use PHPat\Statement\Builder\StatementBuilder;

class ShouldBeFinalStatementBuilder extends DeclarationStatementBuilder
{
    /**
     * @return class-string<ShouldBeFinal>
     */
    protected function getAssertionClassname(): string
    {
        return ShouldBeFinal::class;
    }
}
