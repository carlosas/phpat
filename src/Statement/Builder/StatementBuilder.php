<?php

namespace PhpAT\Statement\Builder;

interface StatementBuilder
{
    /**
     * @return array<class-string, array<class-string>>
     */
    public function build(): array;
}
