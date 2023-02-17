<?php

declare(strict_types=1);

namespace Tests\PHPat\unit\rules\ShouldNotExtend;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Relation\ShouldNotExtend\ParentClassRule;
use PHPat\Rule\Assertion\Relation\ShouldNotExtend\ShouldNotExtend;
use PHPat\Selector\Classname;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PHPat\Test\TestName;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\fixtures\FixtureClass;
use Tests\PHPat\fixtures\Simple\SimpleAbstractClass;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<ParentClassRule>
 */
class ParentClassTest extends RuleTestCase
{
    public const TEST_FUNCTION_NAME_DETECTED_BY_PARSER = 'test_FixtureClassShouldNotExtendSimpleAbstractClass';

    public function testRule(): void
    {
        $this->analyse(['tests/fixtures/FixtureClass.php'], [
            [sprintf('%s: %s should not extend %s', self::TEST_FUNCTION_NAME_DETECTED_BY_PARSER, FixtureClass::class, SimpleAbstractClass::class), 31],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            new TestName(self::TEST_FUNCTION_NAME_DETECTED_BY_PARSER),
            ShouldNotExtend::class,
            [new Classname(FixtureClass::class, false)],
            [new Classname(SimpleAbstractClass::class, false)]
        );

        return new ParentClassRule(
            new StatementBuilderFactory($testParser),
            new Configuration(false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
