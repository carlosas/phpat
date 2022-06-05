<?php

declare(strict_types=1);

namespace PHPat\Statement\Builder;

use PHPat\Rule\Assertion\ShouldExtend\ShouldExtend;

class ShouldExtendStatementBuilder extends StatementBuilder
{
    protected function getAssertionClassname(): string
    {
        return ShouldExtend::class;
    }
}
