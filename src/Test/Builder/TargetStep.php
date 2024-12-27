<?php declare(strict_types=1);

namespace PHPat\Test\Builder;

use PHPat\Selector\SelectorInterface;

class TargetStep extends AbstractStep
{
    public function classes(SelectorInterface ...$selectors): TargetExcludeOrBuildStep
    {
        $this->rule->targets = $selectors;

        return new TargetExcludeOrBuildStep($this->rule);
    }
}
