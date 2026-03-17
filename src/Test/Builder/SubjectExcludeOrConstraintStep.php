<?php declare(strict_types=1);

namespace PHPat\Test\Builder;

use PHPat\Selector\SelectorInterface;

class SubjectExcludeOrConstraintStep extends ConstraintStep
{
    public function excluding(SelectorInterface ...$selectors): ConstraintStep
    {
        $this->rule->subjectExcludes = $selectors;

        return new ConstraintStep($this->rule);
    }
}
