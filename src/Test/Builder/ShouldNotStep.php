<?php declare(strict_types=1);

namespace PHPat\Test\Builder;

class ShouldNotStep extends AbstractStep
{
    public function dependOn(): TargetStep
    {
        $this->rule->assertionType = 'depend';

        return new TargetStep($this->rule);
    }

    public function extend(): TargetStep
    {
        $this->rule->assertionType = 'extend';

        return new TargetStep($this->rule);
    }

    public function implement(): TargetStep
    {
        $this->rule->assertionType = 'implement';

        return new TargetStep($this->rule);
    }

    public function include(): TargetStep
    {
        $this->rule->assertionType = 'include';

        return new TargetStep($this->rule);
    }

    public function construct(): TargetStep
    {
        $this->rule->assertionType = 'construct';

        return new TargetStep($this->rule);
    }

    public function beAbstract(): TipOrBuildStep
    {
        $this->rule->assertionType = 'beAbstract';

        return new TipOrBuildStep($this->rule);
    }

    public function beFinal(): TipOrBuildStep
    {
        $this->rule->assertionType = 'beFinal';

        return new TipOrBuildStep($this->rule);
    }

    public function beReadonly(): TipOrBuildStep
    {
        $this->rule->assertionType = 'beReadonly';

        return new TipOrBuildStep($this->rule);
    }

    public function beEnum(): TipOrBuildStep
    {
        $this->rule->assertionType = 'beEnum';

        return new TipOrBuildStep($this->rule);
    }

    public function beInvokable(): TipOrBuildStep
    {
        $this->rule->assertionType = 'beInvokable';

        return new TipOrBuildStep($this->rule);
    }

    public function exist(): TipOrBuildStep
    {
        $this->rule->assertionType = 'exist';

        return new TipOrBuildStep($this->rule);
    }
}
