<?php

declare(strict_types=1);

namespace Tests\PHPat\unit\rules\ShouldNotBeFinal;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Declaration\ShouldNotBeFinal\IsFinalRule;
use PHPat\Rule\Assertion\Declaration\ShouldNotBeFinal\ShouldNotBeFinal;
use PHPat\Selector\Classname;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PHPat\Test\TestName;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\fixtures\Simple\SimpleFinalClass;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<IsFinalRule>
 */
class FinalClassTest extends RuleTestCase
{
    public const TEST_FUNCTION_NAME_DETECTED_BY_PARSER = 'test_SimpleFinalClassShouldNotBeFinal';

    public function testRule(): void
    {
        $this->analyse(['tests/fixtures/Simple/SimpleFinalClass.php'], [
            [sprintf('%s: %s should not be final', self::TEST_FUNCTION_NAME_DETECTED_BY_PARSER, SimpleFinalClass::class), 7],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            new TestName(self::TEST_FUNCTION_NAME_DETECTED_BY_PARSER),
            ShouldNotBeFinal::class,
            [new Classname(SimpleFinalClass::class, false)],
            []
        );

        return new IsFinalRule(
            new StatementBuilderFactory($testParser),
            new Configuration(false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
