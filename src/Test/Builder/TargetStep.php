<?php

namespace PHPat\Test\Builder;

use PHPat\Rule\Assertion\ShouldImplement\ShouldImplement;
use PHPat\Rule\Assertion\ShouldNotConstruct\ShouldNotConstruct;
use PHPat\Rule\Assertion\ShouldNotDepend\ShouldNotDepend;
use PHPat\Rule\Assertion\ShouldNotExtend\ShouldNotExtend;
use PHPat\Rule\Assertion\ShouldNotImplement\ShouldNotImplement;
use PHPat\Selector\SelectorInterface;
use PHPat\Test\PHPat;
use PHPat\Test\Rule;

class TargetStep
{
    private Rule $rule;

    public function __construct(Rule $rule)
    {
        $this->rule = $rule;
    }

    public function classes(SelectorInterface ...$selectors): TargetExcludeOrAssertionStep
    {
        $this->rule->targets = [...$selectors];

        return new TargetExcludeOrAssertionStep($this->rule);
    }
}
