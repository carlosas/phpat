<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\CanOnlyDepend;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Relation\CanOnlyDepend\CanOnlyDepend;
use PHPat\Rule\Assertion\Relation\CanOnlyDepend\InstanceofRule;
use PHPat\Selector\Classname;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\fixtures\Simple\SimpleClass;
use Tests\PHPat\fixtures\Simple\SimpleException;
use Tests\PHPat\fixtures\Simple\SimpleInterface;
use Tests\PHPat\fixtures\Special\ClassWithInstanceof;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<InstanceofRule>
 * @internal
 * @coversNothing
 */
class InstanceofTest extends RuleTestCase
{
    public const RULE_NAME = 'testClassWithInstanceofCanOnlyDependSimpleClass';

    public function testRule(): void
    {
        $this->analyse(['tests/fixtures/Special/ClassWithInstanceof.php'], [
            [sprintf('%s should not depend on %s', ClassWithInstanceof::class, SimpleException::class), 18],
            [sprintf('%s should not depend on %s', ClassWithInstanceof::class, SimpleException::class), 23],
            [sprintf('%s should not depend on %s', ClassWithInstanceof::class, SimpleException::class), 37],
            [sprintf('%s should not depend on %s', ClassWithInstanceof::class, SimpleInterface::class), 45],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            self::RULE_NAME,
            CanOnlyDepend::class,
            [new Classname(ClassWithInstanceof::class, false)],
            [new Classname(SimpleClass::class, false)]
        );

        return new InstanceofRule(
            new StatementBuilderFactory($testParser),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}