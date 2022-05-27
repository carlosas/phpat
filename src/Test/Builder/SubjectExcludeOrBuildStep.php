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

class SubjectExcludeOrBuildStep extends AssertionStep
{
    public function excluding(SelectorInterface ...$selectors): AssertionStep
    {
        $this->rule->subjectExcludes = [...$selectors];

        return new AssertionStep($this->rule);
    }
}
