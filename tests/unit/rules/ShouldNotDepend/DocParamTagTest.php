<?php declare(strict_types=1);

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

/**
 * @extends RuleTestCase<DocParamTagRule>
 * @internal
 * @coversNothing
 */
class DocParamTagTest extends RuleTestCase
{
    public const RULE_NAME = 'testFixtureClassShouldNotDependSimpleAndSpecial';

    public function testRule(): void
    {
        $this->analyse(['tests/fixtures/FixtureClass.php'], [
            [sprintf('%s should not depend on %s', FixtureClass::class, SimpleClass::class), 72],
            [sprintf('%s should not depend on %s', FixtureClass::class, SimpleClassTwo::class), 72],
            [sprintf('%s should not depend on %s', FixtureClass::class, SimpleClassThree::class), 72],
            [sprintf('%s should not depend on %s', FixtureClass::class, SimpleClassFour::class), 72],
            [sprintf('%s should not depend on %s', FixtureClass::class, SimpleClassFive::class), 72],
            [sprintf('%s should not depend on %s', FixtureClass::class, SimpleClassSix::class), 72],
            [sprintf('%s should not depend on %s', FixtureClass::class, InterfaceWithTemplate::class), 72],
            [sprintf('%s should not depend on %s', FixtureClass::class, ClassImplementing::class), 72],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            self::RULE_NAME,
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
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
