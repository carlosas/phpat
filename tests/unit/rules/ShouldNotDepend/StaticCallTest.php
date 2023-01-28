<?php

declare(strict_types=1);

namespace Tests\PHPat\Unit\Rules\ShouldNotDepend;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Relation\ShouldNotDepend\ShouldNotDepend;
use PHPat\Rule\Assertion\Relation\ShouldNotDepend\StaticMethodRule;
use PHPat\Selector\Classname;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\Fixtures\FixtureClass;
use Tests\PHPat\Fixtures\Special\ClassWithStaticMethod;
use Tests\PHPat\Unit\FakeTestParser;
use Tests\PHPat\Unit\ErrorMessage;

/**
 * @extends RuleTestCase<StaticMethodRule>
 */
class StaticCallTest extends RuleTestCase
{
    public function testRule(): void
    {
        $this->analyse(['tests/fixtures/FixtureClass.php'], [
            [sprintf(ErrorMessage::SHOULD_NOT_DEPEND, FixtureClass::class, ClassWithStaticMethod::class), 65],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            ShouldNotDepend::class,
            [new Classname(FixtureClass::class, false)],
            [new Classname(ClassWithStaticMethod::class, false)]
        );

        return new StaticMethodRule(
            new StatementBuilderFactory($testParser),
            new Configuration(false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
