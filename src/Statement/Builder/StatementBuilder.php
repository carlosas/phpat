<?php

namespace PhpAT\Statement\Builder;

use PhpAT\Selector\Selector;

interface StatementBuilder
{
    /**
     * @return array<array{Selector, array<Selector>}>
     */
    public function build(): array;
}
