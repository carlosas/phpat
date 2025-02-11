<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\ShouldHaveOnlyPublicMethodNamed;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Declaration\ShouldHaveOnlyPublicMethodNamed\HasOnlyPublicMethodNamedRule;
use PHPat\Rule\Assertion\Declaration\ShouldHaveOnlyPublicMethodNamed\HasOnlyPublicPublicMethodNamedRule;
use PHPat\Rule\Assertion\Declaration\ShouldHaveOnlyPublicMethodNamed\ShouldHaveOnlyPublicMethodNamed;
use PHPat\Selector\Classname;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\fixtures\Special\ClassWithOnlyPublicMethodNamed;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<HasOnlyPublicPublicMethodNamedRule>
 * @internal
 * @coversNothing
 */
class GoodImplementationClassWithOnlyPublicMethodNamedWithRegexTest extends RuleTestCase
{
    public const RULE_NAME = 'testFixtureClassShouldHaveOnlyPublicMethodNamed';

    public function testRule(): void
    {
        $this->analyse(['tests/fixtures/Special/ClassWithOnlyPublicMethodNamed.php'], []);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            self::RULE_NAME,
            ShouldHaveOnlyPublicMethodNamed::class,
            [new Classname(ClassWithOnlyPublicMethodNamed::class, false)],
            [],
            [],
            ['name' => '/^method[a-zA-Z0-9]+/', 'isRegex' => true]
        );

        return new HasOnlyPublicMethodNamedRule(
            new StatementBuilderFactory($testParser),
            new Configuration(false, true, false),
            self::createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
