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
use Tests\PHPat\fixtures\Simple\SimpleAttribute;
use Tests\PHPat\fixtures\Simple\SimpleClass;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<ClassAttributeRule>
 * @internal
 * @coversNothing
 */
class SimpleClassAttributeTest extends RuleTestCase
{
    public const RULE_NAME = 'testSimpleClassShouldApplySimpleAttribute';

    public function testRule(): void
    {
        $this->analyse(['tests/fixtures/Simple/SimpleClass.php'], [
            [sprintf('%s should apply the attribute %s', SimpleClass::class, SimpleAttribute::class), 5],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            self::RULE_NAME,
            ShouldApplyAttribute::class,
            [new Classname(SimpleClass::class, false)],
            [new Classname(SimpleAttribute::class, false)]
        );

        return new ClassAttributeRule(
            new StatementBuilderFactory($testParser),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
