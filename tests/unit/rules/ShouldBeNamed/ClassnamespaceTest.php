<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\ShouldBeNamed;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Declaration\Named\ClassnameRule;
use PHPat\Selector\ClassNamespace;
use PHPat\Statement\StatementBuilder;
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
    public const RULE_NAME = 'testFixtureClassUnderNamespaceShouldBeNamed';

    public function testRule(): void
    {
        // Class under FooBar should not subject to the rule
        $this->analyse(['tests/fixtures/Ns/FooBar/ClassUnderFooBarNamespace.php'], []);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            self::RULE_NAME,
            Constraint::Should,
            'beNamed',
            [new ClassNamespace('Tests\PHPat\fixtures\Ns\Foo', false)],
            [],
            [],
            ['isRegex' => false, 'classname' => ClassUnderFooNamespace::class]
        );

        return new ClassnameRule(
            new StatementBuilder($testParser),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
