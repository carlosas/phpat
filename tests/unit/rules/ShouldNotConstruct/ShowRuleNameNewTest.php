<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\ShouldNotConstruct;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Relation\ShouldNotConstruct\NewRule;
use PHPat\Rule\Assertion\Relation\ShouldNotConstruct\ShouldNotConstruct;
use PHPat\Selector\Classname;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\fixtures\FixtureClass;
use Tests\PHPat\fixtures\Simple\SimpleClass;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<NewRule>
 * @internal
 * @coversNothing
 */
class ShowRuleNameNewTest extends RuleTestCase
{
    public const RULE_NAME = 'test_FixtureClassShouldNotConstructSimpleClass';

    public function testRule(): void
    {
        $this->analyse(['tests/fixtures/FixtureClass.php'], [
            [sprintf('%s: %s should not construct %s', self::RULE_NAME, FixtureClass::class, SimpleClass::class), 49],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            self::RULE_NAME,
            ShouldNotConstruct::class,
            [new Classname(FixtureClass::class, false)],
            [new Classname(SimpleClass::class, false)]
        );

        return new NewRule(
            new StatementBuilderFactory($testParser),
            new Configuration(false, true, true),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
