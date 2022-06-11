<?php

declare(strict_types=1);

namespace PHPat\Statement\Builder;

use PHPat\Rule\Assertion\ShouldNotExtend\ShouldNotExtend;

class ShouldNotExtendStatementBuilder extends StatementBuilder
{
    /**
     * @return class-string<ShouldNotExtend>
     */
    protected function getAssertionClassname(): string
    {
        return ShouldNotExtend::class;
    }
}
