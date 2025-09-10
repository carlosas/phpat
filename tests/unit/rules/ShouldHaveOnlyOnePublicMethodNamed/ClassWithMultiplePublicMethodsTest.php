<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\ShouldHaveOnlyOnePublicMethodNamed;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Declaration\ShouldHaveOnlyOnePublicMethodNamed\HasOnlyOnePublicMethodNamedRule;
use PHPat\Rule\Assertion\Declaration\ShouldHaveOnlyOnePublicMethodNamed\ShouldHaveOnlyOnePublicMethodNamed;
use PHPat\Selector\Classname;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\fixtures\Special\ClassWithMultiplePublicMethods;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<HasOnlyOnePublicMethodNamedRule>
 * @coversNothing
 * @internal
 */
class ClassWithMultiplePublicMethodsTest extends RuleTestCase
{
    public const RULE_NAME = 'testFixtureClassShouldHaveOnlyOnePublicMethodNamed';

    public function testRule(): void
    {
        $this->analyse(['tests/fixtures/Special/ClassWithMultiplePublicMethods.php'], [
            [sprintf('%s should have only one public method named %s', ClassWithMultiplePublicMethods::class, 'targetMethod'), 5],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            self::RULE_NAME,
            ShouldHaveOnlyOnePublicMethodNamed::class,
            [new Classname(ClassWithMultiplePublicMethods::class, false)],
            [],
            [],
            ['name' => 'targetMethod', 'isRegex' => false]
        );

        return new HasOnlyOnePublicMethodNamedRule(
            new StatementBuilderFactory($testParser),
            new Configuration(false, true, false),
            self::createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
