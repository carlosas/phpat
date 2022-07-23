<?php

declare(strict_types=1);

namespace Tests\PHPat\unit\rules\ShouldNotDepend;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Relation\ShouldNotDepend\ShouldNotDepend;
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
 * @extends RuleTestCase<\PHPat\Rule\Assertion\Relation\ShouldNotDepend\AllDocBlockRelationsRule>
 */
class AllDocBlockRelationsWithIgnoreTest extends RuleTestCase
{
    public function testRule(): void
    {
        $this->analyse(['tests/fixtures/FixtureClass.php'], []);
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
        $configuration->method('ignoreDocComments')->willReturn(true);

        return new \PHPat\Rule\Assertion\Relation\ShouldNotDepend\AllDocBlockRelationsRule(
            new StatementBuilderFactory($testParser),
            $configuration,
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
