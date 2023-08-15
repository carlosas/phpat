<?php declare(strict_types=1);

namespace PHPat\Test\Builder;

use PHPat\Test\RelationRule;

abstract class AbstractStep implements Rule
{
    protected RelationRule $rule;

    final public function __construct(RelationRule $rule)
    {
        $this->rule = $rule;
    }

    final public function __invoke(): RelationRule
    {
        return $this->rule;
    }
}
