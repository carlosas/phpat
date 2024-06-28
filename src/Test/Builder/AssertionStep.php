<?php declare(strict_types=1);

namespace PHPat\Test\Builder;

use PHPat\Rule\Assertion\Declaration\ShouldBeAbstract\ShouldBeAbstract;
use PHPat\Rule\Assertion\Declaration\ShouldBeFinal\ShouldBeFinal;
use PHPat\Rule\Assertion\Declaration\ShouldBeInterface\ShouldBeInterface;
use PHPat\Rule\Assertion\Declaration\ShouldBeNamed\ShouldBeNamed;
use PHPat\Rule\Assertion\Declaration\ShouldBeReadonly\ShouldBeReadonly;
use PHPat\Rule\Assertion\Declaration\ShouldHaveOnlyOnePublicMethod\ShouldHaveOnlyOnePublicMethod;
use PHPat\Rule\Assertion\Declaration\ShouldNotBeAbstract\ShouldNotBeAbstract;
use PHPat\Rule\Assertion\Declaration\ShouldNotBeFinal\ShouldNotBeFinal;
use PHPat\Rule\Assertion\Declaration\ShouldNotBeReadonly\ShouldNotBeReadonly;
use PHPat\Rule\Assertion\Declaration\ShouldNotExist\ShouldNotExist;
use PHPat\Rule\Assertion\Relation\CanOnlyDepend\CanOnlyDepend;
use PHPat\Rule\Assertion\Relation\ShouldApplyAttribute\ShouldApplyAttribute;
use PHPat\Rule\Assertion\Relation\ShouldExtend\ShouldExtend;
use PHPat\Rule\Assertion\Relation\ShouldImplement\ShouldImplement;
use PHPat\Rule\Assertion\Relation\ShouldInclude\ShouldInclude;
use PHPat\Rule\Assertion\Relation\ShouldNotConstruct\ShouldNotConstruct;
use PHPat\Rule\Assertion\Relation\ShouldNotDepend\ShouldNotDepend;
use PHPat\Rule\Assertion\Relation\ShouldNotExtend\ShouldNotExtend;
use PHPat\Rule\Assertion\Relation\ShouldNotImplement\ShouldNotImplement;
use PHPat\Rule\Assertion\Relation\ShouldNotInclude\ShouldNotInclude;

class AssertionStep extends AbstractStep
{
    public function shouldBeNamed(string $classname, bool $regex = false): TipOrBuildStep
    {
        $this->rule->assertion = ShouldBeNamed::class;
        $this->rule->params = ['isRegex' => $regex, 'classname' => $classname];

        return new TipOrBuildStep($this->rule);
    }

    public function shouldBeAbstract(): TipOrBuildStep
    {
        $this->rule->assertion = ShouldBeAbstract::class;

        return new TipOrBuildStep($this->rule);
    }

    public function shouldNotBeAbstract(): TipOrBuildStep
    {
        $this->rule->assertion = ShouldNotBeAbstract::class;

        return new TipOrBuildStep($this->rule);
    }

    public function shouldBeReadonly(): TipOrBuildStep
    {
        $this->rule->assertion = ShouldBeReadonly::class;

        return new TipOrBuildStep($this->rule);
    }

    public function shouldNotBeReadonly(): TipOrBuildStep
    {
        $this->rule->assertion = ShouldNotBeReadonly::class;

        return new TipOrBuildStep($this->rule);
    }

    public function shouldBeFinal(): TipOrBuildStep
    {
        $this->rule->assertion = ShouldBeFinal::class;

        return new TipOrBuildStep($this->rule);
    }

    public function shouldNotBeFinal(): TipOrBuildStep
    {
        $this->rule->assertion = ShouldNotBeFinal::class;

        return new TipOrBuildStep($this->rule);
    }

    public function shouldNotDependOn(): TargetStep
    {
        $this->rule->assertion = ShouldNotDepend::class;

        return new TargetStep($this->rule);
    }

    public function canOnlyDependOn(): TargetStep
    {
        $this->rule->assertion = CanOnlyDepend::class;

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

    public function shouldNotInclude(): TargetStep
    {
        $this->rule->assertion = ShouldNotInclude::class;

        return new TargetStep($this->rule);
    }

    public function shouldInclude(): TargetStep
    {
        $this->rule->assertion = ShouldInclude::class;

        return new TargetStep($this->rule);
    }

    public function shouldExtend(): TargetStep
    {
        $this->rule->assertion = ShouldExtend::class;

        return new TargetStep($this->rule);
    }

    public function shouldHaveOnlyOnePublicMethod(): TipOrBuildStep
    {
        $this->rule->assertion = ShouldHaveOnlyOnePublicMethod::class;

        return new TipOrBuildStep($this->rule);
    }

    public function shouldApplyAttribute(): TargetStep
    {
        $this->rule->assertion = ShouldApplyAttribute::class;

        return new TargetStep($this->rule);
    }

    public function shouldBeInterface(): TipOrBuildStep
    {
        $this->rule->assertion = ShouldBeInterface::class;

        return new TipOrBuildStep($this->rule);
    }

    public function shouldNotExist(): TipOrBuildStep
    {
        $this->rule->assertion = ShouldNotExist::class;

        return new TipOrBuildStep($this->rule);
    }
}
