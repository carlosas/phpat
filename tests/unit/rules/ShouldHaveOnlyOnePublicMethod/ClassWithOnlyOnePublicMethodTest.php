<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\ShouldHaveOnlyOnePublicMethod;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Declaration\ShouldHaveOnlyOnePublicMethod\HasOnlyOnePublicMethodRule;
use PHPat\Rule\Assertion\Declaration\ShouldHaveOnlyOnePublicMethod\ShouldHaveOnlyOnePublicMethod;
use PHPat\Selector\Classname;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\fixtures\FixtureClass;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<HasOnlyOnePublicMethodRule>
 * @internal
 * @coversNothing
 */
class ClassWithOnlyOnePublicMethodTest extends RuleTestCase
{
    public const RULE_NAME = 'testFixtureClassShouldHaveOnlyOnePublicMethod';

    public function testRule(): void
    {
        $this->analyse(['tests/fixtures/FixtureClass.php'], [
            [sprintf('%s should have only one public method', FixtureClass::class), 29],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            self::RULE_NAME,
            ShouldHaveOnlyOnePublicMethod::class,
            [new Classname(FixtureClass::class, false)],
            []
        );

        return new HasOnlyOnePublicMethodRule(
            new StatementBuilderFactory($testParser),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
