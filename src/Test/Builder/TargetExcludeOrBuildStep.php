<?php

declare(strict_types=1);

namespace PHPat\Test\Builder;

use PHPat\Selector\SelectorInterface;

class TargetExcludeOrBuildStep extends BuildStep
{
    public function excluding(SelectorInterface ...$selectors): BuildStep
    {
        $this->rule->targetExcludes = array_values($selectors);

        return new BuildStep($this->rule);
    }
}
