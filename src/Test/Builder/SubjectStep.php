<?php

declare(strict_types=1);

namespace PHPat\Test\Builder;

use PHPat\Selector\SelectorInterface;
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
