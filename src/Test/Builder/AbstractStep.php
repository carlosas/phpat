<?php declare(strict_types=1);

namespace PHPat\Test\Builder;

use PHPat\Test\RelationRule;

abstract class AbstractStep implements Rule
{
    protected RelationRule $rule;

    protected const NON_IGNORABLE_PARAM = 'nonIgnorable';

    final public function __construct(RelationRule $rule)
    {
        $this->rule = $rule;
    }

    final public function __invoke(): RelationRule
    {
        return $this->rule;
    }

    public function nonIgnoreable(): static
    {
        $this->rule->params[self::NON_IGNORABLE_PARAM] = true;

        return $this;
    }

    public function nonIgnorable(): static
    {
        return $this->nonIgnoreable();
    }
}
