<?php

declare(strict_types=1);

namespace Tests\PHPat\unit\rules\ShouldNotDepend;

use PHPat\Configuration;
use PHPat\Rule\Assertion\ShouldNotDepend\AllDocBlockRelationsRule;
use PHPat\Rule\Assertion\ShouldNotDepend\ShouldNotDepend;
use PHPat\Selector\Classname;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\fixtures\FixtureClass;
use Tests\PHPat\fixtures\Simple\SimpleClass;
use Tests\PHPat\fixtures\Simple\SimpleClassFive;
use Tests\PHPat\fixtures\Simple\SimpleClassFour;
use Tests\PHPat\fixtures\Simple\SimpleClassSix;
use Tests\PHPat\fixtures\Simple\SimpleClassThree;
use Tests\PHPat\fixtures\Simple\SimpleClassTwo;
use Tests\PHPat\fixtures\Simple\SimpleException;
use Tests\PHPat\fixtures\Simple\SimpleInterface;
use Tests\PHPat\fixtures\Special\ClassImplementing;
use Tests\PHPat\fixtures\Special\InterfaceWithTemplate;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<AllDocBlockRelationsRule>
 */
class AllDocBlockRelationsWithoutIgnoreTest extends RuleTestCase
{
    public function testRule(): void
    {
        $this->analyse(['tests/fixtures/FixtureClass.php'], [
            [sprintf('%s should not depend on %s', FixtureClass::class, SimpleClass::class), 28],
            [sprintf('%s should not depend on %s', FixtureClass::class, SimpleClassTwo::class), 28],
            [sprintf('%s should not depend on %s', FixtureClass::class, SimpleClassThree::class), 28],
            [sprintf('%s should not depend on %s', FixtureClass::class, SimpleClassFour::class), 28],
            [sprintf('%s should not depend on %s', FixtureClass::class, SimpleClassFive::class), 28],
            [sprintf('%s should not depend on %s', FixtureClass::class, SimpleClassSix::class), 28],
            [sprintf('%s should not depend on %s', FixtureClass::class, SimpleClass::class), 64],
            [sprintf('%s should not depend on %s', FixtureClass::class, SimpleClassTwo::class), 64],
            [sprintf('%s should not depend on %s', FixtureClass::class, SimpleClassThree::class), 64],
            [sprintf('%s should not depend on %s', FixtureClass::class, SimpleClassFour::class), 64],
            [sprintf('%s should not depend on %s', FixtureClass::class, SimpleClassFive::class), 64],
            [sprintf('%s should not depend on %s', FixtureClass::class, SimpleClassSix::class), 64],
            [sprintf('%s should not depend on %s', FixtureClass::class, InterfaceWithTemplate::class), 64],
            [sprintf('%s should not depend on %s', FixtureClass::class, ClassImplementing::class), 64],
            [sprintf('%s should not depend on %s', FixtureClass::class, SimpleException::class), 64],
            [sprintf('%s should not depend on %s', FixtureClass::class, SimpleInterface::class), 64],
            [sprintf('%s should not depend on %s', FixtureClass::class, SimpleClass::class), 67],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            ShouldNotDepend::class,
            [new Classname(FixtureClass::class)],
            [
                new Classname(SimpleClass::class),
                new Classname(SimpleClassTwo::class),
                new Classname(SimpleClassThree::class),
                new Classname(SimpleClassFour::class),
                new Classname(SimpleClassFive::class),
                new Classname(SimpleClassSix::class),
                new Classname(InterfaceWithTemplate::class),
                new Classname(ClassImplementing::class),
                new Classname(SimpleException::class),
                new Classname(SimpleInterface::class),
            ]
        );

        $configuration = $this->createMock(Configuration::class);
        $configuration->method('ignoreDocComments')->willReturn(false);

        return new AllDocBlockRelationsRule(
            new StatementBuilderFactory($testParser),
            $configuration,
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
