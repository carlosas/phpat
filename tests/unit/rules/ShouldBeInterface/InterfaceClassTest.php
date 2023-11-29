<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\ShouldBeInterface;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Declaration\ShouldBeInterface\IsInterfaceRule;
use PHPat\Rule\Assertion\Declaration\ShouldBeInterface\ShouldBeInterface;
use PHPat\Selector\Classname;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\fixtures\Simple\SimpleClass;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<IsInterfaceRule>
 * @internal
 * @coversNothing
 */
class InterfaceClassTest extends RuleTestCase
{
    public const RULE_NAME = 'test_FixtureClassShouldBeInterface';

    public function testRule(): void
    {
        $this->analyse(['tests/fixtures/Simple/SimpleClass.php'], [
            [sprintf('%s should be an interface', SimpleClass::class), 5],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            self::RULE_NAME,
            ShouldBeInterface::class,
            [new Classname(SimpleClass::class, false)],
            []
        );

        return new IsInterfaceRule(
            new StatementBuilderFactory($testParser),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
