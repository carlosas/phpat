<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\ShouldInclude;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Relation\ShouldInclude\IncludedTraitsRule;
use PHPat\Rule\Assertion\Relation\ShouldInclude\ShouldInclude;
use PHPat\Selector\Classname;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\fixtures\FixtureClass;
use Tests\PHPat\fixtures\Simple\SimpleTraitTwo;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<IncludedTraitsRule>
 * @internal
 * @coversNothing
 */
class ShowRuleNameIncludedTraitsTest extends RuleTestCase
{
    public const RULE_NAME = 'testFixtureClassShouldIncludeSimpleTraitTwo';

    public function testRule(): void
    {
        $this->analyse(['tests/fixtures/FixtureClass.php'], [
            [sprintf('%s: %s should include %s', self::RULE_NAME, FixtureClass::class, SimpleTraitTwo::class), 29],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            self::RULE_NAME,
            ShouldInclude::class,
            [new Classname(FixtureClass::class, false)],
            [new Classname(SimpleTraitTwo::class, false)]
        );

        return new IncludedTraitsRule(
            new StatementBuilderFactory($testParser),
            new Configuration(false, true, true),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
