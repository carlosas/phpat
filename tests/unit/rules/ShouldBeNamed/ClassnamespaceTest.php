<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\ShouldBeNamed;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Declaration\ShouldBeNamed\ClassnameRule;
use PHPat\Rule\Assertion\Declaration\ShouldBeNamed\ShouldBeNamed;
use PHPat\Selector\ClassNamespace;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\fixtures\Ns\Foo\ClassUnderFooNamespace;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<ClassnameRule>
 * @internal
 * @coversNothing
 */
class ClassnamespaceTest extends RuleTestCase
{
    public const RULE_NAME = 'test_FixtureClassUnderNamespaceShouldBeNamed';

    public function testRule(): void
    {
        // Class under FooBar should not subject to the rule
        $this->analyse(['tests/fixtures/Ns/FooBar/ClassUnderFooBarNamespace.php'], []);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            self::RULE_NAME,
            ShouldBeNamed::class,
            [new ClassNamespace('Tests\PHPat\fixtures\Namespace\Foo', false)],
            [],
            [],
            ['isRegex' => false, 'classname' => ClassUnderFooNamespace::class]
        );

        return new ClassnameRule(
            new StatementBuilderFactory($testParser),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
