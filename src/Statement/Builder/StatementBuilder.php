<?php

namespace PhpAT\Statement\Builder;

use PhpAT\Selector\Selector;

interface StatementBuilder
{
    /**
     * @return array<array{Selector, array<class-string>}>
     */
    public function build(): array;
}
