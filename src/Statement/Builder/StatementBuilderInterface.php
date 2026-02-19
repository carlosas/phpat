<?php declare(strict_types=1);

namespace PHPat\Statement\Builder;

use PHPat\Statement\Statement;

interface StatementBuilderInterface
{
    /**
     * @return array<Statement>
     */
    public function build(): array;
}
