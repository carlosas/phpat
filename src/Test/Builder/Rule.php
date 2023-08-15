<?php declare(strict_types=1);

namespace PHPat\Test\Builder;

use PHPat\Test\RelationRule;

interface Rule
{
    public function __invoke(): RelationRule;
}
