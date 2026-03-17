<?php declare(strict_types=1);

namespace PHPat\Test\Builder;

use PHPat\Selector\SelectorInterface;

class TargetExcludeOrTipOrBuildStep extends TipOrBuildStep
{
    public function excluding(SelectorInterface ...$selectors): TipOrBuildStep
    {
        $this->rule->targetExcludes = $selectors;

        return new TipOrBuildStep($this->rule);
    }
}
