<?php

declare(strict_types=1);

namespace PHPat\Statement\Builder\Declaration;

use PHPat\Rule\Assertion\Declaration\ShouldNotBeFinal\ShouldNotBeFinal;
use PHPat\Statement\Builder\DeclarationStatementBuilder;

class ShouldNotBeFinalStatementBuilder extends DeclarationStatementBuilder
{
    /**
     * @return class-string<ShouldNotBeFinal>
     */
    protected function getAssertionClassname(): string
    {
        return ShouldNotBeFinal::class;
    }
}
