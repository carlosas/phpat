<?php declare(strict_types=1);

namespace PHPat\Test\Builder;

use PHPat\Selector\SelectorInterface;

class TargetExcludeOrBuildStep extends TipOrBuildStep
{
    public function excluding(SelectorInterface ...$selectors): TipOrBuildStep
    {
        $this->rule->targetExcludes = array_values($selectors);

        return new TipOrBuildStep($this->rule);
    }
}
