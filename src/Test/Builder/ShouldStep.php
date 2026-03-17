<?php declare(strict_types=1);

namespace PHPat\Test\Builder;

class ShouldStep extends AbstractStep
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

    public function applyAttribute(): TargetStep
    {
        $this->rule->assertionType = 'applyAttribute';

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

    public function beInterface(): TipOrBuildStep
    {
        $this->rule->assertionType = 'beInterface';

        return new TipOrBuildStep($this->rule);
    }

    public function beNamed(string $classname, bool $regex = false): TipOrBuildStep
    {
        $this->rule->assertionType = 'beNamed';
        $this->rule->params = ['isRegex' => $regex, 'classname' => $classname];

        return new TipOrBuildStep($this->rule);
    }

    public function haveOnlyOnePublicMethod(): TipOrBuildStep
    {
        $this->rule->assertionType = 'haveOnlyOnePublicMethod';

        return new TipOrBuildStep($this->rule);
    }

    public function haveOnlyOnePublicMethodNamed(string $name, bool $isRegex = false): TipOrBuildStep
    {
        $this->rule->assertionType = 'haveOnlyOnePublicMethodNamed';
        $this->rule->params = ['name' => $name, 'isRegex' => $isRegex];

        return new TipOrBuildStep($this->rule);
    }
}
