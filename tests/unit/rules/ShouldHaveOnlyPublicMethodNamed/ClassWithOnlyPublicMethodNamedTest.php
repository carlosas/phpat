<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\ShouldHaveOnlyPublicMethodNamed;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Declaration\ShouldHaveOnlyPublicMethodNamed\HasOnlyPublicMethodNamedRule;
use PHPat\Rule\Assertion\Declaration\ShouldHaveOnlyPublicMethodNamed\ShouldHaveOnlyPublicMethodNamed;
use PHPat\Selector\Classname;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\fixtures\FixtureClass;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<HasOnlyPublicPublicMethodNamedRule>
 * @internal
 * @coversNothing
 */
class ClassWithOnlyPublicMethodNamedTest extends RuleTestCase
{
    public const RULE_NAME = 'testFixtureClassShouldHaveOnlyPublicMethodNamed';

    public function testRule(): void
    {
        $this->analyse(['tests/fixtures/FixtureClass.php'], [
            [sprintf('%s should have only public methods named', FixtureClass::class), 29],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            self::RULE_NAME,
            ShouldHaveOnlyPublicMethodNamed::class,
            [new Classname(FixtureClass::class, false)],
            []
        );

        return new HasOnlyPublicMethodNamedRule(
            new StatementBuilderFactory($testParser),
            new Configuration(false, true, false),
            self::createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
