<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\ShouldBeInterface;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Declaration\IsInterface\IsInterfaceRule;
use PHPat\Selector\Classname;
use PHPat\Statement\StatementBuilder;
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
    public const RULE_NAME = 'testFixtureClassShouldBeInterface';

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
            Constraint::Should, 'beInterface',
            [new Classname(SimpleClass::class, false)],
            []
        );

        return new IsInterfaceRule(
            new StatementBuilder($testParser),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
