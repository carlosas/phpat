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
use Tests\PHPat\fixtures\Special\ClassWithOnePublicMethod;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<HasOnlyOnePublicMethodRule>
 * @internal
 * @coversNothing
 */
class GoodImplementationClassWithOnlyOnePublicMethodTest extends RuleTestCase
{
    public const RULE_NAME = 'test_FixtureClassShouldHaveOnlyOnePublicMethod';

    public function testRule(): void
    {
        $this->analyse(['tests/fixtures/Special/ClassWithOnePublicMethod.php'], []);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            self::RULE_NAME,
            ShouldHaveOnlyOnePublicMethod::class,
            [new Classname(ClassWithOnePublicMethod::class, false)],
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
