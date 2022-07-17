<?php

declare(strict_types=1);

namespace Tests\PHPat\unit\rules\ShouldImplement;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Relation\ShouldImplement\ImplementedInterfacesRule;
use PHPat\Selector\Classname;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\fixtures\FixtureClass;
use Tests\PHPat\fixtures\Simple\SimpleInterfaceTwo;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<\PHPat\Rule\Assertion\Relation\ShouldImplement\ImplementedInterfacesRule>
 */
class ImplementedInterfacesTest extends RuleTestCase
{
    public function testRule(): void
    {
        $this->analyse(['tests/fixtures/FixtureClass.php'], [
            [sprintf('%s should implement %s', FixtureClass::class, SimpleInterfaceTwo::class), 28],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            \PHPat\Rule\Assertion\Relation\ShouldImplement\ShouldImplement::class,
            [new Classname(FixtureClass::class)],
            [new Classname(SimpleInterfaceTwo::class)]
        );

        return new \PHPat\Rule\Assertion\Relation\ShouldImplement\ImplementedInterfacesRule(
            new StatementBuilderFactory($testParser),
            $this->createMock(Configuration::class),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
