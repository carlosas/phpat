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

class SubjectStep
{
    private Rule $rule;

    public function __construct(Rule $rule)
    {
        $this->rule = $rule;
    }

    public function classes(SelectorInterface ...$selectors): SubjectExcludeOrBuildStep
    {
        $this->rule->subjects = [...$selectors];

        return new SubjectExcludeOrBuildStep($this->rule);
    }
}
