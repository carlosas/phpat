<?php declare(strict_types=1);

namespace PHPat\Test\Builder;

use PHPat\Selector\SelectorInterface;

class SubjectStep extends AbstractStep
{
    public function classes(SelectorInterface ...$selectors): SubjectExcludeOrConstraintStep
    {
        $this->rule->subjects = $selectors;

        return new SubjectExcludeOrConstraintStep($this->rule);
    }
}
