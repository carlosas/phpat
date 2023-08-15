<?php declare(strict_types=1);

namespace PHPat\Statement\Builder;

interface StatementBuilder
{
    /**
     * @return array<mixed>
     */
    public function build(): array;
}
