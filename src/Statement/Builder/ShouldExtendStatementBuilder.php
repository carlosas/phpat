<?php

declare(strict_types=1);

namespace PHPat\Statement\Builder;

use PHPat\Rule\Assertion\Relation\ShouldExtend\ShouldExtend;

class ShouldExtendStatementBuilder extends StatementBuilder
{
    /**
     * @return class-string<ShouldExtend>
     */
    protected function getAssertionClassname(): string
    {
        return ShouldExtend::class;
    }
}
