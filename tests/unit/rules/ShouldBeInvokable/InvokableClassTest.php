<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\ShouldBeInvokable;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Declaration\ShouldBeInvokable\IsInvokableRule;
use PHPat\Rule\Assertion\Declaration\ShouldBeInvokable\ShouldBeInvokable;
use PHPat\Selector\Classname;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\fixtures\FixtureClass;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<IsInvokableRule>
 * @internal
 * @coversNothing
 */
class InvokableClassTest extends RuleTestCase
{
    public const RULE_NAME = 'testFixtureClassShouldBeInvokable';

    public function testRule(): void
    {
        $this->analyse(['tests/fixtures/FixtureClass.php'], [
            [sprintf('%s should be invokable', FixtureClass::class), 29],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            self::RULE_NAME,
            ShouldBeInvokable::class,
            [new Classname(FixtureClass::class, false)],
            []
        );

        return new IsInvokableRule(
            new StatementBuilderFactory($testParser),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
