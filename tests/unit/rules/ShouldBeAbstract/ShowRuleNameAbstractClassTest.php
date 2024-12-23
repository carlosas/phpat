<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\ShouldBeAbstract;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Declaration\ShouldBeAbstract\AbstractRule;
use PHPat\Rule\Assertion\Declaration\ShouldBeAbstract\ShouldBeAbstract;
use PHPat\Selector\Classname;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\fixtures\FixtureClass;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<AbstractRule>
 * @internal
 * @coversNothing
 */
class ShowRuleNameAbstractClassTest extends RuleTestCase
{
    public const RULE_NAME = 'testFixtureClassShouldBeAbstract';

    public function testRule(): void
    {
        $this->analyse(['tests/fixtures/FixtureClass.php'], [
            [sprintf('%s: %s should be abstract', self::RULE_NAME, FixtureClass::class), 29],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            self::RULE_NAME,
            ShouldBeAbstract::class,
            [new Classname(FixtureClass::class, false)],
            []
        );

        return new AbstractRule(
            new StatementBuilderFactory($testParser),
            new Configuration(false, true, true),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
