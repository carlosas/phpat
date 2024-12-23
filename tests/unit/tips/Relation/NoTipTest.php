<?php declare(strict_types=1);

namespace Tests\PHPat\unit\tips\Relation;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Relation\CanOnlyDepend\AllAttributesRule;
use PHPat\Rule\Assertion\Relation\CanOnlyDepend\CanOnlyDepend;
use PHPat\Selector\Classname;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\fixtures\FixtureClass;
use Tests\PHPat\fixtures\Simple\SimpleAttribute;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<AllAttributesRule>
 * @internal
 * @coversNothing
 */
class NoTipTest extends RuleTestCase
{
    public const RULE_NAME = 'testFixtureClassCanOnlyDependSimpleAndSpecial';

    public function testRule(): void
    {
        $this->analyse(['tests/fixtures/FixtureClass.php'], [
            [sprintf('%s should not depend on %s', FixtureClass::class, SimpleAttribute::class), 29, 'tip #1'],
            [sprintf('%s should not depend on %s', FixtureClass::class, SimpleAttribute::class), 33, 'tip #1'],
            [sprintf('%s should not depend on %s', FixtureClass::class, SimpleAttribute::class), 34, 'tip #1'],
            [sprintf('%s should not depend on %s', FixtureClass::class, SimpleAttribute::class), 94, 'tip #1'],
            [sprintf('%s should not depend on %s', FixtureClass::class, SimpleAttribute::class), 95, 'tip #1'],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            self::RULE_NAME,
            CanOnlyDepend::class,
            [new Classname(FixtureClass::class, false)],
            [new Classname(\Attribute::class, false)],
            ['tip #1']
        );

        return new AllAttributesRule(
            new StatementBuilderFactory($testParser),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
