<?php

namespace PHPat\Test\Builder;

use PHPat\Selector\SelectorInterface;

class SubjectExcludeOrBuildStep extends AssertionStep
{
    public function excluding(SelectorInterface ...$selectors): AssertionStep
    {
        $this->rule->subjectExcludes = [...$selectors];

        return new AssertionStep($this->rule);
    }
}
