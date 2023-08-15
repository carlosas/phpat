<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\CanOnlyDepend;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Relation\CanOnlyDepend\CanOnlyDepend;
use PHPat\Rule\Assertion\Relation\CanOnlyDepend\ConstantUseRule;
use PHPat\Selector\Classname;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\fixtures\FixtureClass;
use Tests\PHPat\fixtures\Special\ClassWithConstant;
use Tests\PHPat\fixtures\Special\ClassWithConstantTwo;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<\PHPat\Rule\Assertion\Relation\CanOnlyDepend\ConstantUseRule>
 * @internal
 * @coversNothing
 */
class ConstantUseTest extends RuleTestCase
{
    public const RULE_NAME = 'test_FixtureClassCanOnlyDependSimpleAndSpecial';

    public function testRule(): void
    {
        $this->analyse(['tests/fixtures/FixtureClass.php'], [
            [sprintf('%s should not depend on %s', FixtureClass::class, ClassWithConstant::class), 54],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            self::RULE_NAME,
            CanOnlyDepend::class,
            [new Classname(FixtureClass::class, false)],
            [new Classname(ClassWithConstantTwo::class, false)]
        );

        return new ConstantUseRule(
            new StatementBuilderFactory($testParser),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
