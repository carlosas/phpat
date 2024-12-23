<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\ShouldNotInclude;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Relation\ShouldNotInclude\IncludedTraitsRule;
use PHPat\Rule\Assertion\Relation\ShouldNotInclude\ShouldNotInclude;
use PHPat\Selector\Classname;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\fixtures\FixtureClass;
use Tests\PHPat\fixtures\Simple\SimpleTrait;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<IncludedTraitsRule>
 * @internal
 * @coversNothing
 */
class IncludedTraitsTest extends RuleTestCase
{
    public const RULE_NAME = 'testFixtureClassShouldNotIncludeSimpleTrait';

    public function testRule(): void
    {
        $this->analyse(['tests/fixtures/FixtureClass.php'], [
            [sprintf('%s should not include %s', FixtureClass::class, SimpleTrait::class), 29],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            self::RULE_NAME,
            ShouldNotInclude::class,
            [new Classname(FixtureClass::class, false)],
            [new Classname(SimpleTrait::class, false)]
        );

        return new IncludedTraitsRule(
            new StatementBuilderFactory($testParser),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
