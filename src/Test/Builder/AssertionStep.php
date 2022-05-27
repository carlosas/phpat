<?php

namespace PHPat\Test\Builder;

use PHPat\Rule\Assertion\ShouldImplement\ShouldImplement;
use PHPat\Rule\Assertion\ShouldNotConstruct\ShouldNotConstruct;
use PHPat\Rule\Assertion\ShouldNotDepend\ShouldNotDepend;
use PHPat\Rule\Assertion\ShouldNotExtend\ShouldNotExtend;
use PHPat\Rule\Assertion\ShouldNotImplement\ShouldNotImplement;
use PHPat\Test\Rule;

class AssertionStep
{
    protected Rule $rule;

    public function __construct(Rule $rule)
    {
        $this->rule = $rule;
    }

    public function shouldNotDependOn(): TargetStep
    {
        $this->rule->assertion = ShouldNotDepend::class;

        return new TargetStep($this->rule);
    }

    public function shouldNotConstruct(): TargetStep
    {
        $this->rule->assertion = ShouldNotConstruct::class;

        return new TargetStep($this->rule);
    }

    public function shouldNotExtend(): TargetStep
    {
        $this->rule->assertion = ShouldNotExtend::class;

        return new TargetStep($this->rule);
    }

    public function shouldNotImplement(): TargetStep
    {
        $this->rule->assertion = ShouldNotImplement::class;

        return new TargetStep($this->rule);
    }

    public function shouldImplement(): TargetStep
    {
        $this->rule->assertion = ShouldImplement::class;

        return new TargetStep($this->rule);
    }

    /*public function shouldImplement(): TargetStep
    {
        $this->rule->assertion = ShouldImplement::class;

        return new TargetStep($this->rule);
    }*/
}
