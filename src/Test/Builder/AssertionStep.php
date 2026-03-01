<?php declare(strict_types=1);

namespace PHPat\Test\Builder;

use PHPat\Rule\Assertion\Constraint;

class AssertionStep extends AbstractStep
{
    public function should(): ShouldStep
    {
        $this->rule->constraint = Constraint::Should;

        return new ShouldStep($this->rule);
    }

    public function shouldNot(): ShouldNotStep
    {
        $this->rule->constraint = Constraint::ShouldNot;

        return new ShouldNotStep($this->rule);
    }

    public function canOnly(): CanOnlyStep
    {
        $this->rule->constraint = Constraint::CanOnly;

        return new CanOnlyStep($this->rule);
    }

    // Deprecated aliases for backward compatibility

    /**
     * @deprecated Use ->should()->beNamed()
     */
    public function shouldBeNamed(string $classname, bool $regex = false): TipOrBuildStep
    {
        return $this->should()->beNamed($classname, $regex);
    }

    /**
     * @deprecated Use ->should()->beAbstract()
     */
    public function shouldBeAbstract(): TipOrBuildStep
    {
        return $this->should()->beAbstract();
    }

    /**
     * @deprecated Use ->shouldNot()->beAbstract()
     */
    public function shouldNotBeAbstract(): TipOrBuildStep
    {
        return $this->shouldNot()->beAbstract();
    }

    /**
     * @deprecated Use ->should()->beReadonly()
     */
    public function shouldBeReadonly(): TipOrBuildStep
    {
        return $this->should()->beReadonly();
    }

    /**
     * @deprecated Use ->shouldNot()->beReadonly()
     */
    public function shouldNotBeReadonly(): TipOrBuildStep
    {
        return $this->shouldNot()->beReadonly();
    }

    /**
     * @deprecated Use ->should()->beFinal()
     */
    public function shouldBeFinal(): TipOrBuildStep
    {
        return $this->should()->beFinal();
    }

    /**
     * @deprecated Use ->shouldNot()->beFinal()
     */
    public function shouldNotBeFinal(): TipOrBuildStep
    {
        return $this->shouldNot()->beFinal();
    }

    /**
     * @deprecated Use ->should()->beEnum()
     */
    public function shouldBeEnum(): TipOrBuildStep
    {
        return $this->should()->beEnum();
    }

    /**
     * @deprecated Use ->shouldNot()->beEnum()
     */
    public function shouldNotBeEnum(): TipOrBuildStep
    {
        return $this->shouldNot()->beEnum();
    }

    /**
     * @deprecated Use ->should()->beInvokable()
     */
    public function shouldBeInvokable(): TipOrBuildStep
    {
        return $this->should()->beInvokable();
    }

    /**
     * @deprecated Use ->shouldNot()->beInvokable()
     */
    public function shouldNotBeInvokable(): TipOrBuildStep
    {
        return $this->shouldNot()->beInvokable();
    }

    /**
     * @deprecated Use ->shouldNot()->dependOn()
     */
    public function shouldNotDependOn(): TargetStep
    {
        return $this->shouldNot()->dependOn();
    }

    /**
     * @deprecated Use ->canOnly()->dependOn()
     */
    public function canOnlyDependOn(): TargetStep
    {
        return $this->canOnly()->dependOn();
    }

    /**
     * @deprecated Use ->shouldNot()->construct()
     */
    public function shouldNotConstruct(): TargetStep
    {
        return $this->shouldNot()->construct();
    }

    /**
     * @deprecated Use ->shouldNot()->extend()
     */
    public function shouldNotExtend(): TargetStep
    {
        return $this->shouldNot()->extend();
    }

    /**
     * @deprecated Use ->shouldNot()->implement()
     */
    public function shouldNotImplement(): TargetStep
    {
        return $this->shouldNot()->implement();
    }

    /**
     * @deprecated Use ->should()->implement()
     */
    public function shouldImplement(): TargetStep
    {
        return $this->should()->implement();
    }

    /**
     * @deprecated Use ->shouldNot()->include()
     */
    public function shouldNotInclude(): TargetStep
    {
        return $this->shouldNot()->include();
    }

    /**
     * @deprecated Use ->should()->include()
     */
    public function shouldInclude(): TargetStep
    {
        return $this->should()->include();
    }

    /**
     * @deprecated Use ->should()->extend()
     */
    public function shouldExtend(): TargetStep
    {
        return $this->should()->extend();
    }

    /**
     * @deprecated Use ->should()->haveOnlyOnePublicMethod()
     */
    public function shouldHaveOnlyOnePublicMethod(): TipOrBuildStep
    {
        return $this->should()->haveOnlyOnePublicMethod();
    }

    /**
     * @deprecated Use ->should()->haveOnlyOnePublicMethodNamed()
     */
    public function shouldHaveOnlyOnePublicMethodNamed(string $name, bool $isRegex = false): TipOrBuildStep
    {
        return $this->should()->haveOnlyOnePublicMethodNamed($name, $isRegex);
    }

    /**
     * @deprecated Use ->should()->applyAttribute()
     */
    public function shouldApplyAttribute(): TargetStep
    {
        return $this->should()->applyAttribute();
    }

    /**
     * @deprecated Use ->should()->beInterface()
     */
    public function shouldBeInterface(): TipOrBuildStep
    {
        return $this->should()->beInterface();
    }

    /**
     * @deprecated Use ->shouldNot()->exist()
     */
    public function shouldNotExist(): TipOrBuildStep
    {
        return $this->shouldNot()->exist();
    }
}
