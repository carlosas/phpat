<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\ShouldNotDepend;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Relation\ShouldNotDepend\NewRule;
use PHPat\Rule\Assertion\Relation\ShouldNotDepend\ShouldNotDepend;
use PHPat\Selector\Classname;
use PHPat\Selector\IsStandardClass;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\fixtures\Special\ClassWithStandardClasses;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<NewRule>
 * @internal
 * @coversNothing
 */
class IsStandardClassTest extends RuleTestCase
{
    public const RULE_NAME = 'testClassWithStandardClassInstanceofShouldNotDependOnStandardClasses';

    public function testRule(): void
    {
        // This test verifies that the IsStandardClass selector works with relation assertions
        // ClassWithStandardClassInstanceof uses new with standard PHP classes
        // With ignore_built_in_classes = false, these should be detected as violations
        $this->analyse(['tests/fixtures/Special/ClassWithStandardClasses.php'], [
            [sprintf('%s should not depend on %s', ClassWithStandardClasses::class, 'Exception'), 9],
            [sprintf('%s should not depend on %s', ClassWithStandardClasses::class, 'stdClass'), 14],
            [sprintf('%s should not depend on %s', ClassWithStandardClasses::class, 'Error'), 19],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            self::RULE_NAME,
            ShouldNotDepend::class,
            [new Classname(ClassWithStandardClasses::class, false)],
            [new IsStandardClass()]
        );

        return new NewRule(
            new StatementBuilderFactory($testParser),
            new Configuration(false, false, false), // ignore_built_in_classes = false
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
