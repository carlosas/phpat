<?php

declare(strict_types=1);

namespace Tests\PHPat\unit\rules\ShouldNotImplement;

use PHPat\Configuration;
use PHPat\Selector\Classname;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\fixtures\FixtureClass;
use Tests\PHPat\fixtures\Simple\SimpleInterface;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<\PHPat\Rule\Assertion\Relation\ShouldNotImplement\ImplementedInterfacesRule>
 */
class ImplementedInterfacesTest extends RuleTestCase
{
    public function testRule(): void
    {
        $this->analyse(['tests/fixtures/FixtureClass.php'], [
            [sprintf('%s should not implement %s', FixtureClass::class, SimpleInterface::class), 28],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            \PHPat\Rule\Assertion\Relation\ShouldNotImplement\ShouldNotImplement::class,
            [new Classname(FixtureClass::class)],
            [new Classname(SimpleInterface::class)]
        );

        return new \PHPat\Rule\Assertion\Relation\ShouldNotImplement\ImplementedInterfacesRule(
            new StatementBuilderFactory($testParser),
            $this->createMock(Configuration::class),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
