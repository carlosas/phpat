<?php

declare(strict_types=1);

namespace Tests\PHPat\Unit\Rules\ShouldNotImplement;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Relation\ShouldNotImplement\ImplementedInterfacesRule;
use PHPat\Rule\Assertion\Relation\ShouldNotImplement\ShouldNotImplement;
use PHPat\Selector\Classname;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\Fixtures\FixtureClass;
use Tests\PHPat\Fixtures\Simple\SimpleInterface;
use Tests\PHPat\Unit\FakeTestParser;
use Tests\PHPat\Unit\ErrorMessage;

/**
 * @extends RuleTestCase<ImplementedInterfacesRule>
 */
class ImplementedInterfacesTest extends RuleTestCase
{
    public function testRule(): void
    {
        $this->analyse(['tests/fixtures/FixtureClass.php'], [
            [sprintf(ErrorMessage::SHOULD_NOT_IMPLEMENT, FixtureClass::class, SimpleInterface::class), 31],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            ShouldNotImplement::class,
            [new Classname(FixtureClass::class, false)],
            [new Classname(SimpleInterface::class, false)]
        );

        return new ImplementedInterfacesRule(
            new StatementBuilderFactory($testParser),
            new Configuration(false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
