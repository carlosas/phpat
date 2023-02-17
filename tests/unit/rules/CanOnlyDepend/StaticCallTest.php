<?php

declare(strict_types=1);

namespace Tests\PHPat\unit\rules\CanOnlyDepend;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Relation\CanOnlyDepend\CanOnlyDepend;
use PHPat\Rule\Assertion\Relation\CanOnlyDepend\StaticMethodRule;
use PHPat\Selector\Classname;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PHPat\Test\TestName;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\fixtures\FixtureClass;
use Tests\PHPat\fixtures\Special\ClassWithStaticMethod;
use Tests\PHPat\fixtures\Special\ClassWithStaticMethodTwo;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<StaticMethodRule>
 */
class StaticCallTest extends RuleTestCase
{
    public const TEST_FUNCTION_NAME_DETECTED_BY_PARSER = 'test_FixtureClassCanOnlyDependSimpleAndSpecial';

    public function testRule(): void
    {
        $this->analyse(['tests/fixtures/FixtureClass.php'], [
            [sprintf('%s: %s should not depend on %s', self::TEST_FUNCTION_NAME_DETECTED_BY_PARSER, FixtureClass::class, ClassWithStaticMethod::class), 61],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            new TestName(self::TEST_FUNCTION_NAME_DETECTED_BY_PARSER),
            CanOnlyDepend::class,
            [new Classname(FixtureClass::class, false)],
            [new Classname(ClassWithStaticMethodTwo::class, false)]
        );

        return new StaticMethodRule(
            new StatementBuilderFactory($testParser),
            new Configuration(false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
