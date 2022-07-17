<?php

declare(strict_types=1);

namespace PHPat\Test\Builder;

use PHPat\Rule\Assertion\Relation\ShouldImplement\ShouldImplement;
use PHPat\Rule\Assertion\Relation\ShouldNotConstruct\ShouldNotConstruct;
use PHPat\Rule\Assertion\Relation\ShouldNotDepend\ShouldNotDepend;
use PHPat\Rule\Assertion\Relation\ShouldNotExtend\ShouldNotExtend;
use PHPat\Rule\Assertion\Relation\ShouldNotImplement\ShouldNotImplement;
use PHPat\Rule\Assertion\Relation\ShouldExtend\ShouldExtend;
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

    public function shouldExtend(): TargetStep
    {
        $this->rule->assertion = ShouldExtend::class;

        return new TargetStep($this->rule);
    }
}
