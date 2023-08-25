<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\ShouldImplement;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Relation\ShouldImplement\ImplementedInterfacesRule;
use PHPat\Rule\Assertion\Relation\ShouldImplement\ShouldImplement;
use PHPat\Selector\Classname;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\fixtures\Simple\SimpleClass;
use Tests\PHPat\fixtures\Simple\SimpleInterface;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<ImplementedInterfacesRule>
 * @internal
 * @coversNothing
 */
class SimpleImplementedInterfacesTest extends RuleTestCase
{
    public const RULE_NAME = 'test_SimpleClassShouldImplementSimpleInterface';

    public function testRule(): void
    {
        $this->analyse(['tests/fixtures/Simple/SimpleClass.php'], [
            [sprintf('%s should implement %s', SimpleClass::class, SimpleInterface::class), 5],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            self::RULE_NAME,
            ShouldImplement::class,
            [new Classname(SimpleClass::class, false)],
            [new Classname(SimpleInterface::class, false)]
        );

        return new ImplementedInterfacesRule(
            new StatementBuilderFactory($testParser),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
