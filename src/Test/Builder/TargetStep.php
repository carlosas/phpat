<?php

declare(strict_types=1);

namespace PHPat\Test\Builder;

use PHPat\Selector\SelectorInterface;
use PHPat\Test\Rule;

class TargetStep
{
    private Rule $rule;

    public function __construct(Rule $rule)
    {
        $this->rule = $rule;
    }

    public function classes(SelectorInterface ...$selectors): TargetExcludeOrBuildStep
    {
        $this->rule->targets = array_values($selectors);

        return new TargetExcludeOrBuildStep($this->rule);
    }
}
