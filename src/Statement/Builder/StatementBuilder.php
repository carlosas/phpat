<?php

declare(strict_types=1);

namespace PHPat\Statement\Builder;

interface StatementBuilder
{
    public function build(): array;
}
