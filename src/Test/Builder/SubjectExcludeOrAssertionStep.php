<?php declare(strict_types=1);

namespace PHPat\Test\Builder;

use PHPat\Selector\SelectorInterface;

class SubjectExcludeOrAssertionStep extends AssertionStep
{
    public function excluding(SelectorInterface ...$selectors): AssertionStep
    {
        $this->rule->subjectExcludes = $selectors;

        return new AssertionStep($this->rule);
    }
}
