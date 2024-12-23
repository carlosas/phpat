<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\ShouldNotExtend;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Relation\ShouldNotExtend\ParentClassRule;
use PHPat\Rule\Assertion\Relation\ShouldNotExtend\ShouldNotExtend;
use PHPat\Selector\Classname;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\fixtures\FixtureClass;
use Tests\PHPat\fixtures\Simple\SimpleAbstractClass;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<ParentClassRule>
 * @internal
 * @coversNothing
 */
class ParentClassTest extends RuleTestCase
{
    public const RULE_NAME = 'testFixtureClassShouldNotExtendSimpleAbstractClass';

    public function testRule(): void
    {
        $this->analyse(['tests/fixtures/FixtureClass.php'], [
            [sprintf('%s should not extend %s', FixtureClass::class, SimpleAbstractClass::class), 29],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            self::RULE_NAME,
            ShouldNotExtend::class,
            [new Classname(FixtureClass::class, false)],
            [new Classname(SimpleAbstractClass::class, false)]
        );

        return new ParentClassRule(
            new StatementBuilderFactory($testParser),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
