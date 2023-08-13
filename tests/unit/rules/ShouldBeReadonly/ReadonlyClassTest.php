<?php

declare(strict_types=1);

namespace Tests\PHPat\unit\rules\ShouldBeReadonly;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Declaration\ShouldBeReadonly\IsReadonlyRule;
use PHPat\Rule\Assertion\Declaration\ShouldBeReadonly\ShouldBeReadonly;
use PHPat\Selector\Classname;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\fixtures\FixtureClass;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<IsReadonlyRule>
 */
class ReadonlyClassTest extends RuleTestCase
{
    public const RULE_NAME = 'test_FixtureClassShouldBeReadonly';
    public function testRule(): void
    {
        $this->analyse(['tests/fixtures/FixtureClass.php'], [
            [sprintf('%s should be readonly', FixtureClass::class), 31],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            self::RULE_NAME,
            ShouldBeReadonly::class,
            [new Classname(FixtureClass::class, false)],
            []
        );

        return new IsReadonlyRule(
            new StatementBuilderFactory($testParser),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
