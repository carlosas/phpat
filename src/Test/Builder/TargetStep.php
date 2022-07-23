<?php

declare(strict_types=1);

namespace PHPat\Test\Builder;

use PHPat\Selector\SelectorInterface;
use PHPat\Test\RelationRule;

class TargetStep extends AbstractStep
{
    public function classes(SelectorInterface ...$selectors): TargetExcludeOrBuildStep
    {
        $this->rule->targets = array_values($selectors);

        return new TargetExcludeOrBuildStep($this->rule);
    }
}
