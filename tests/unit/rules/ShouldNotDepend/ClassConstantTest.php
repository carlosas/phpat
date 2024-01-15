<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\ShouldNotDepend;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Relation\ShouldNotDepend\ClassPropertyRule;
use PHPat\Rule\Assertion\Relation\ShouldNotDepend\ShouldNotDepend;
use PHPat\Selector\Classname;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\fixtures\FixtureClass;
use Tests\PHPat\fixtures\Php83FixtureClass;
use Tests\PHPat\fixtures\Simple\SimpleClass;
use Tests\PHPat\fixtures\Simple\SimpleInterface;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<ClassPropertyRule>
 * @internal
 * @coversNothing
 */
class ClassConstantTest extends RuleTestCase
{
    public const RULE_NAME = 'test_FixtureClassShouldNotDependSimpleAndSpecial';

    public function testRule(): void
    {
        if (PHP_VERSION_ID < 80300) {
            $this->markTestSkipped('This test is only for PHP 8.3+');
        }

        $this->analyse(['tests/fixtures/FixtureClass.php'], [
            [sprintf('%s should not depend on %s', Php83FixtureClass::class, SimpleClass::class), 8],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            self::RULE_NAME,
            ShouldNotDepend::class,
            [new Classname(Php83FixtureClass::class, false)],
            [new Classname(SimpleClass::class, false)]
        );

        return new ClassPropertyRule(
            new StatementBuilderFactory($testParser),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
