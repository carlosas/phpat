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
use Tests\PHPat\fixtures\Special\ClassInvokable;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<IsInvokableRule>
 * @internal
 * @coversNothing
 */
class GoodImplementationInvokableClassTest extends RuleTestCase
{
    public const RULE_NAME = 'testFixtureClassShouldBeInvokable';

    public function testRule(): void
    {
        $this->analyse(['tests/fixtures/Special/ClassInvokable.php'], []);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            self::RULE_NAME,
            ShouldBeInvokable::class,
            [new Classname(ClassInvokable::class, false)],
            []
        );

        return new IsInvokableRule(
            new StatementBuilderFactory($testParser),
            new Configuration(false, true, true),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
