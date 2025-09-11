<?php

namespace Tests\PHPat\unit\rules\ShouldNotBeEnum;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Declaration\ShouldNotBeEnum\IsEnumRule;
use PHPat\Rule\Assertion\Declaration\ShouldNotBeEnum\ShouldNotBeEnum;
use PHPat\Selector\Classname;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\fixtures\Simple\SimpleEnum;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<IsEnumRule>
 * @internal
 * @coversNothing
 */
class EnumTest extends RuleTestCase
{
    public const RULE_NAME = 'testFixtureClassShouldNotBeEnum';

    public function testRule(): void
    {
        $this->analyse(['tests/fixtures/Simple/SimpleEnum.php'], [
            [sprintf('%s should not be enum', SimpleEnum::class), 5],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            self::RULE_NAME,
            ShouldNotBeEnum::class,
            [new Classname(SimpleEnum::class, false)],
            []
        );

        return new IsEnumRule(
            new StatementBuilderFactory($testParser),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
