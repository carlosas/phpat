<?php declare(strict_types=1);

namespace PHPat\Test\Builder;

class CanOnlyStep extends AbstractStep
{
    public function dependOn(): TargetStep
    {
        $this->rule->assertionType = 'depend';

        return new TargetStep($this->rule);
    }

    public function construct(): TargetStep
    {
        $this->rule->assertionType = 'construct';

        return new TargetStep($this->rule);
    }
}
