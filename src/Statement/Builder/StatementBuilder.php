<?php

namespace PhpAT\Statement\Builder;

use PhpAT\Selector\SelectorInterface;

interface StatementBuilder
{
    /**
     * @return array<array{SelectorInterface, array<SelectorInterface>}>
     */
    public function build(): array;
}
