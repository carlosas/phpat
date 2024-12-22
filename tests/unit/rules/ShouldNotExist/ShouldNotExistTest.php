<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\ShouldNotExist;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Declaration\ShouldNotExist\ExistsRule;
use PHPat\Rule\Assertion\Declaration\ShouldNotExist\ShouldNotExist;
use PHPat\Selector\Classname;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\fixtures\FixtureClass;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<ShouldNotExist>
 * @internal
 * @coversNothing
 */
class ShouldNotExistTest extends RuleTestCase
{
    public const RULE_NAME = 'testFixtureClassShouldNotExist';

    public function testRule(): void
    {
        $this->analyse(['tests/fixtures/FixtureClass.php'], [
            [sprintf('%s should not exist', FixtureClass::class), 29],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            self::RULE_NAME,
            ShouldNotExist::class,
            [new Classname(FixtureClass::class, false)],
            []
        );

        return new ExistsRule(
            new StatementBuilderFactory($testParser),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
