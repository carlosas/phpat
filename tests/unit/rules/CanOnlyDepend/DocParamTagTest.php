<?php

declare(strict_types=1);

namespace Tests\PHPat\Unit\Rules\CanOnlyDepend;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Relation\CanOnlyDepend\DocParamTagRule;
use PHPat\Rule\Assertion\Relation\CanOnlyDepend\CanOnlyDepend;
use PHPat\Selector\Classname;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\Fixtures\FixtureClass;
use Tests\PHPat\Fixtures\Simple\SimpleClass;
use Tests\PHPat\Fixtures\Simple\SimpleClassFive;
use Tests\PHPat\Fixtures\Simple\SimpleClassFour;
use Tests\PHPat\Fixtures\Simple\SimpleClassSix;
use Tests\PHPat\Fixtures\Simple\SimpleClassThree;
use Tests\PHPat\Fixtures\Simple\SimpleClassTwo;
use Tests\PHPat\Fixtures\Simple\SimpleException;
use Tests\PHPat\Fixtures\Simple\SimpleInterface;
use Tests\PHPat\Fixtures\Special\ClassImplementing;
use Tests\PHPat\Fixtures\Special\InterfaceWithTemplate;
use Tests\PHPat\Unit\FakeTestParser;
use Tests\PHPat\Unit\ErrorMessage;

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
            CanOnlyDepend::class,
            [new Classname(FixtureClass::class, false)],
            [
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
