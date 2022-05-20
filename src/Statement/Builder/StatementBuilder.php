<?php

namespace PHPat\Statement\Builder;

use PHPat\Selector\SelectorInterface;

interface StatementBuilder
{
    /**
     * @return array<array{SelectorInterface, array<SelectorInterface>}>
     */
    public function build(): array;
}
