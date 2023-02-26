<?php

declare(strict_types=1);

namespace Tests\PHPat\unit\rules\ShouldNotDepend;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Relation\ShouldNotDepend\DocParamTagRule;
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
use Tests\PHPat\unit\ErrorMessage;

/**
 * @extends RuleTestCase<DocParamTagRule>
 */
class DocParamTagTest extends RuleTestCase
{
    public function testRule(): void
    {
        $this->analyse(['tests/fixtures/FixtureClass.php'], [
            [sprintf(ErrorMessage::SHOULD_NOT_DEPEND, FixtureClass::class, SimpleClass::class), 78],
            [sprintf(ErrorMessage::SHOULD_NOT_DEPEND, FixtureClass::class, SimpleClassTwo::class), 78],
            [sprintf(ErrorMessage::SHOULD_NOT_DEPEND, FixtureClass::class, SimpleClassThree::class), 78],
            [sprintf(ErrorMessage::SHOULD_NOT_DEPEND, FixtureClass::class, SimpleClassFour::class), 78],
            [sprintf(ErrorMessage::SHOULD_NOT_DEPEND, FixtureClass::class, SimpleClassFive::class), 78],
            [sprintf(ErrorMessage::SHOULD_NOT_DEPEND, FixtureClass::class, SimpleClassSix::class), 78],
            [sprintf(ErrorMessage::SHOULD_NOT_DEPEND, FixtureClass::class, InterfaceWithTemplate::class), 78],
            [sprintf(ErrorMessage::SHOULD_NOT_DEPEND, FixtureClass::class, ClassImplementing::class), 78],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            ShouldNotDepend::class,
            [new Classname(FixtureClass::class, false)],
            [
                new Classname(SimpleClass::class, false),
                new Classname(SimpleClassTwo::class, false),
                new Classname(SimpleClassThree::class, false),
                new Classname(SimpleClassFour::class, false),
                new Classname(SimpleClassFive::class, false),
                new Classname(SimpleClassSix::class, false),
                new Classname(InterfaceWithTemplate::class, false),
                new Classname(ClassImplementing::class, false),
                new Classname(SimpleException::class, false),
                new Classname(SimpleInterface::class, false),
            ]
        );

        return new DocParamTagRule(
            new StatementBuilderFactory($testParser),
            new Configuration(false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
