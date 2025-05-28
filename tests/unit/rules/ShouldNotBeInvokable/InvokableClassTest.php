<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\ShouldNotBeInvokable;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Declaration\ShouldNotBeInvokable\IsInvokableRule;
use PHPat\Rule\Assertion\Declaration\ShouldNotBeInvokable\ShouldNotBeInvokable;
use PHPat\Selector\Classname;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\fixtures\Special\ClassInvokable;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<IsInvokableRule>
 * @internal
 * @coversNothing
 */
class InvokableClassTest extends RuleTestCase
{
    public const RULE_NAME = 'testFixtureClassShouldNotBeInvokable';

    public function testRule(): void
    {
        $this->analyse(['tests/fixtures/Special/ClassInvokable.php'], [
            [sprintf('%s should not be invokable', ClassInvokable::class), 6],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            self::RULE_NAME,
            ShouldNotBeInvokable::class,
            [new Classname(ClassInvokable::class, false)],
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
