<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\ShouldHaveOnlyOnePublicMethodNamed;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Declaration\ShouldHaveOnlyOnePublicMethodNamed\HasOnlyOnePublicMethodNamedRule;
use PHPat\Selector\Classname;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\fixtures\FixtureClass;
use Tests\PHPat\fixtures\Special\ClassWithTwoUnrelatedNamedMethods;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<HasOnlyOnePublicMethodNamedRule>
 * @internal
 * @coversNothing
 */
class MoreThanOnePublicMethodNamedWithRegexTest extends RuleTestCase
{
    public const RULE_NAME = 'testFixtureClassShouldHaveOnlyOnePublicMethodNamed';

    public function testRule(): void
    {
        // Should succeed as 'foo' is not named like 'ba*'
        $this->analyse(['tests/fixtures/Special/ClassWithTwoUnrelatedNamedMethods.php'], []);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            self::RULE_NAME,
            ClassWithTwoUnrelatedNamedMethods::class,
            [new Classname(FixtureClass::class, false)],
            [],
            [],
            ['name' => '/^ba[a-zA-Z0-9]+/', 'isRegex' => true]
        );

        return new HasOnlyOnePublicMethodNamedRule(
            new StatementBuilderFactory($testParser),
            new Configuration(false, true, false),
            self::createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
