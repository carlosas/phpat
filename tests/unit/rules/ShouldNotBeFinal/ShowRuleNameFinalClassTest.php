<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\ShouldNotBeFinal;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Declaration\ShouldNotBeFinal\IsFinalRule;
use PHPat\Rule\Assertion\Declaration\ShouldNotBeFinal\ShouldNotBeFinal;
use PHPat\Selector\Classname;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\fixtures\Simple\SimpleFinalClass;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<IsFinalRule>
 *
 * @internal
 *
 * @coversNothing
 */
class ShowRuleNameFinalClassTest extends RuleTestCase
{
    public const RULE_NAME = 'test_SimpleFinalClassShouldNotBeFinal';

    public function testRule(): void
    {
        $this->analyse(['tests/fixtures/Simple/SimpleFinalClass.php'], [
            [sprintf('%s: %s should not be final', self::RULE_NAME, SimpleFinalClass::class), 7],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            self::RULE_NAME,
            ShouldNotBeFinal::class,
            [new Classname(SimpleFinalClass::class, false)],
            []
        );

        return new IsFinalRule(
            new StatementBuilderFactory($testParser),
            new Configuration(false, true, true),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
