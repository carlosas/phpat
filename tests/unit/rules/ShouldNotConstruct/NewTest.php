<?php

declare(strict_types=1);

namespace Tests\PHPat\unit\rules\ShouldNotConstruct;

use PHPat\Rule\Assertion\ShouldNotConstruct\NewRule;
use PHPat\Rule\Assertion\ShouldNotConstruct\ShouldNotConstruct;
use PHPat\Selector\Classname;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\fixtures\FixtureClass;
use Tests\PHPat\fixtures\Simple\SimpleClass;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<NewRule>
 */
class NewTest extends RuleTestCase
{
    public function testRule(): void
    {
        $this->analyse(['tests/fixtures/FixtureClass.php'], [
            [sprintf('%s should not construct %s', FixtureClass::class, SimpleClass::class), 46],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            ShouldNotConstruct::class,
            [new Classname(FixtureClass::class)],
            [new Classname(SimpleClass::class)]
        );

        return new NewRule(
            new StatementBuilderFactory($testParser),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
