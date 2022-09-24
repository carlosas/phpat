<?php

declare(strict_types=1);

namespace Tests\PHPat\unit\rules\ShouldImplement;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Relation\ShouldImplement\ImplementedInterfacesRule;
use PHPat\Rule\Assertion\Relation\ShouldImplement\ShouldImplement;
use PHPat\Selector\Classname;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\fixtures\FixtureClass;
use Tests\PHPat\fixtures\Simple\SimpleInterfaceTwo;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<ImplementedInterfacesRule>
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
            ShouldImplement::class,
            [new Classname(FixtureClass::class, false)],
            [new Classname(SimpleInterfaceTwo::class, false)]
        );

        return new ImplementedInterfacesRule(
            new StatementBuilderFactory($testParser),
            $this->createMock(Configuration::class),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
