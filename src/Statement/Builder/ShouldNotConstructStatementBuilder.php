<?php

declare(strict_types=1);

namespace PHPat\Statement\Builder;

use PHPat\Rule\Assertion\ShouldNotConstruct\ShouldNotConstruct;

class ShouldNotConstructStatementBuilder extends StatementBuilder
{
    /**
     * @return class-string<ShouldNotConstruct>
     */
    protected function getAssertionClassname(): string
    {
        return ShouldNotConstruct::class;
    }
}
