<?php declare(strict_types=1);

namespace PHPat\Test\Builder;

use PHPat\Selector\SelectorInterface;

class SubjectStep extends AbstractStep
{
    public function classes(SelectorInterface ...$selectors): SubjectExcludeOrAssertionStep
    {
        $this->rule->subjects = array_values($selectors);

        return new SubjectExcludeOrAssertionStep($this->rule);
    }
}
