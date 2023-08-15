<?php declare(strict_types=1);

namespace PHPat\Test\Builder;

class TipOrBuildStep extends BuildStep
{
    public function because(string ...$tips): BuildStep
    {
        $this->rule->tips = array_values($tips);

        return new BuildStep($this->rule);
    }
}
