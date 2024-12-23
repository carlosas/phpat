<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\ShouldApplyAttribute;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Relation\ShouldApplyAttribute\ClassAttributeRule;
use PHPat\Rule\Assertion\Relation\ShouldApplyAttribute\ShouldApplyAttribute;
use PHPat\Selector\Classname;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\fixtures\FixtureClass;
use Tests\PHPat\fixtures\Simple\SimpleAttributeTwo;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<ClassAttributeRule>
 * @internal
 * @coversNothing
 */
class ClassAttributeTest extends RuleTestCase
{
    public const RULE_NAME = 'testFixtureClassShouldApplySimpleAttributeTwo';

    public function testRule(): void
    {
        $this->analyse(['tests/fixtures/FixtureClass.php'], [
            [sprintf('%s should apply the attribute %s', FixtureClass::class, SimpleAttributeTwo::class), 29],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            self::RULE_NAME,
            ShouldApplyAttribute::class,
            [new Classname(FixtureClass::class, false)],
            [new Classname(SimpleAttributeTwo::class, false)]
        );

        return new ClassAttributeRule(
            new StatementBuilderFactory($testParser),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
