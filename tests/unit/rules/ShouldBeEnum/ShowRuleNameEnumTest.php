<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\ShouldBeEnum;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Declaration\ShouldBeEnum\IsEnumRule;
use PHPat\Rule\Assertion\Declaration\ShouldBeEnum\ShouldBeEnum;
use PHPat\Selector\Classname;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\fixtures\FixtureClass;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<IsEnumRule>
 * @internal
 * @coversNothing
 */
class ShowRuleNameEnumTest extends RuleTestCase
{
    public const RULE_NAME = 'testFixtureClassShouldBeEnum';

    public function testRule(): void
    {
        $this->analyse(['tests/fixtures/FixtureClass.php'], [
            [sprintf('%s: %s should be enum', self::RULE_NAME, FixtureClass::class), 29],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            self::RULE_NAME,
            ShouldBeEnum::class,
            [new Classname(FixtureClass::class, false)],
            []
        );

        return new IsEnumRule(
            new StatementBuilderFactory($testParser),
            new Configuration(false, true, true),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
