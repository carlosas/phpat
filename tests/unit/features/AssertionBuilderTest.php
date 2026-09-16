<?php declare(strict_types=1);

namespace Tests\PHPat\unit\features;

use PHPat\Rule\Assertion\Constraint;
use PHPat\Selector\Selector;
use PHPat\Test\PHPat;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
class AssertionBuilderTest extends TestCase
{
    public function testBuildsPositiveNameAssertion(): void
    {
        $rule = PHPat::rule()->classes(Selector::all())->should()->beNamed(classname: 'App\Service')();

        self::assertSame(Constraint::Should, $rule->getConstraint());
        self::assertSame('beNamed', $rule->getAssertionType());
        self::assertSame(['isRegex' => false, 'classname' => 'App\Service'], $rule->getParams());
    }

    public function testBuildsPositiveNameAssertionWithRegex(): void
    {
        $rule = PHPat::rule()->classes(Selector::all())->should()->beNamed(classname: '/Service$/', regex: true)();

        self::assertSame(Constraint::Should, $rule->getConstraint());
        self::assertSame('beNamed', $rule->getAssertionType());
        self::assertSame(['isRegex' => true, 'classname' => '/Service$/'], $rule->getParams());
    }

    public function testBuildsPositivePublicMethodCountAssertion(): void
    {
        $rule = PHPat::rule()->classes(Selector::all())->should()->haveOnlyOnePublicMethod()();

        self::assertSame(Constraint::Should, $rule->getConstraint());
        self::assertSame('haveOnlyOnePublicMethod', $rule->getAssertionType());
        self::assertSame([], $rule->getParams());
    }

    public function testBuildsPositivePublicMethodNameAssertion(): void
    {
        $rule = PHPat::rule()->classes(Selector::all())->should()->haveOnlyOnePublicMethodNamed(name: 'run')();

        self::assertSame(Constraint::Should, $rule->getConstraint());
        self::assertSame('haveOnlyOnePublicMethodNamed', $rule->getAssertionType());
        self::assertSame(['name' => 'run', 'isRegex' => false], $rule->getParams());
    }

    public function testBuildsPositivePublicMethodNameAssertionWithRegex(): void
    {
        $rule = PHPat::rule()->classes(Selector::all())->should()->haveOnlyOnePublicMethodNamed(name: '/^run/', isRegex: true)();

        self::assertSame(Constraint::Should, $rule->getConstraint());
        self::assertSame('haveOnlyOnePublicMethodNamed', $rule->getAssertionType());
        self::assertSame(['name' => '/^run/', 'isRegex' => true], $rule->getParams());
    }

    public function testBuildsPositiveAttributeAssertion(): void
    {
        $target = Selector::classname('App\Attribute');
        $rule = PHPat::rule()->classes(Selector::all())->should()->applyAttribute()->classes($target)();

        self::assertSame(Constraint::Should, $rule->getConstraint());
        self::assertSame('applyAttribute', $rule->getAssertionType());
        self::assertSame([$target], $rule->getTargets());
        self::assertSame([], $rule->getTargetExcludes());
    }

    public function testBuildsPositiveAttributeAssertionWithExclusion(): void
    {
        $target = Selector::classname('App\Attribute');
        $rule = PHPat::rule()->classes(Selector::all())->should()->applyAttribute()->classes($target)->excluding($target)();

        self::assertSame(Constraint::Should, $rule->getConstraint());
        self::assertSame('applyAttribute', $rule->getAssertionType());
        self::assertSame([$target], $rule->getTargets());
        self::assertSame([$target], $rule->getTargetExcludes());
    }

    public function testBuildsNegativeNameAssertion(): void
    {
        $rule = PHPat::rule()->classes(Selector::all())->shouldNot()->beNamed(classname: 'App\Service')();

        self::assertSame(Constraint::ShouldNot, $rule->getConstraint());
        self::assertSame('beNamed', $rule->getAssertionType());
        self::assertSame(['isRegex' => false, 'classname' => 'App\Service'], $rule->getParams());
    }

    public function testBuildsNegativeNameAssertionWithRegex(): void
    {
        $rule = PHPat::rule()->classes(Selector::all())->shouldNot()->beNamed(classname: '/Service$/', regex: true)();

        self::assertSame(Constraint::ShouldNot, $rule->getConstraint());
        self::assertSame('beNamed', $rule->getAssertionType());
        self::assertSame(['isRegex' => true, 'classname' => '/Service$/'], $rule->getParams());
    }

    public function testBuildsNegativePublicMethodCountAssertion(): void
    {
        $rule = PHPat::rule()->classes(Selector::all())->shouldNot()->haveOnlyOnePublicMethod()();

        self::assertSame(Constraint::ShouldNot, $rule->getConstraint());
        self::assertSame('haveOnlyOnePublicMethod', $rule->getAssertionType());
        self::assertSame([], $rule->getParams());
    }

    public function testBuildsNegativePublicMethodNameAssertion(): void
    {
        $rule = PHPat::rule()->classes(Selector::all())->shouldNot()->haveOnlyOnePublicMethodNamed(name: 'run')();

        self::assertSame(Constraint::ShouldNot, $rule->getConstraint());
        self::assertSame('haveOnlyOnePublicMethodNamed', $rule->getAssertionType());
        self::assertSame(['name' => 'run', 'isRegex' => false], $rule->getParams());
    }

    public function testBuildsNegativePublicMethodNameAssertionWithRegex(): void
    {
        $rule = PHPat::rule()->classes(Selector::all())->shouldNot()->haveOnlyOnePublicMethodNamed(name: '/^run/', isRegex: true)();

        self::assertSame(Constraint::ShouldNot, $rule->getConstraint());
        self::assertSame('haveOnlyOnePublicMethodNamed', $rule->getAssertionType());
        self::assertSame(['name' => '/^run/', 'isRegex' => true], $rule->getParams());
    }

    public function testBuildsNegativeAttributeAssertion(): void
    {
        $target = Selector::classname('App\Attribute');
        $rule = PHPat::rule()->classes(Selector::all())->shouldNot()->applyAttribute()->classes($target)();

        self::assertSame(Constraint::ShouldNot, $rule->getConstraint());
        self::assertSame('applyAttribute', $rule->getAssertionType());
        self::assertSame([$target], $rule->getTargets());
        self::assertSame([], $rule->getTargetExcludes());
    }

    public function testBuildsNegativeAttributeAssertionWithExclusion(): void
    {
        $target = Selector::classname('App\Attribute');
        $rule = PHPat::rule()->classes(Selector::all())->shouldNot()->applyAttribute()->classes($target)->excluding($target)();

        self::assertSame(Constraint::ShouldNot, $rule->getConstraint());
        self::assertSame('applyAttribute', $rule->getAssertionType());
        self::assertSame([$target], $rule->getTargets());
        self::assertSame([$target], $rule->getTargetExcludes());
    }

    public function testBuildsNegativeInterfaceAssertion(): void
    {
        $rule = PHPat::rule()->classes(Selector::all())->shouldNot()->beInterface()();

        self::assertSame(Constraint::ShouldNot, $rule->getConstraint());
        self::assertSame('beInterface', $rule->getAssertionType());
    }
}
