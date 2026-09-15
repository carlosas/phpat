<?php declare(strict_types=1);

namespace Tests\PHPat\unit\features;

use PHPat\Selector\Selector;
use PHPat\Statement\StatementBuilder;
use PHPat\Test\PHPat;
use PHPat\Test\TestParser;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
class NonIgnorableBuilderTest extends TestCase
{
    public function testParameterizedAssertionsPreserveNonIgnorable(): void
    {
        $named = PHPat::rule()->classes(Selector::all())->should()->beNamed('/Service$/', true)->nonIgnorable()->because('Naming convention')();
        $method = PHPat::rule()->classes(Selector::all())->should()->haveOnlyOnePublicMethodNamed('__invoke')->because('Single entry point')->nonIgnorable()();

        self::assertSame(['isRegex' => true, 'classname' => '/Service$/'], $named->getParams());
        self::assertSame(['name' => '__invoke', 'isRegex' => false], $method->getParams());
        self::assertSame(['Naming convention'], $named->getTips());
        self::assertSame(['Single entry point'], $method->getTips());
        foreach ([$named, $method] as $rule) {
            self::assertTrue($rule->isNonIgnorable());
            $parser = $this->createMock(TestParser::class);
            $parser->method('__invoke')->willReturn([$rule]);
            $statements = (new StatementBuilder($parser))->build($rule->getAssertionType());
            self::assertCount(1, $statements);
            self::assertTrue($statements[0]->nonIgnorable);
            self::assertSame($rule->getParams(), $statements[0]->params);
        }
    }

    public function testNonIgnorableIsUnavailableBeforeTheAssertionIsComplete(): void
    {
        $subject = PHPat::rule();
        $selected = $subject->classes(Selector::all());
        $constraint = $selected->excluding(Selector::classname('Excluded'));
        $should = $constraint->should();
        $shouldNot = $constraint->shouldNot();
        $canOnly = $constraint->canOnly();
        $target = $shouldNot->dependOn();

        foreach ([$subject, $selected, $constraint, $should, $shouldNot, $canOnly, $target] as $step) {
            self::assertFalse(method_exists($step, 'nonIgnorable'), $step::class);
        }
    }
}
