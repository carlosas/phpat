<?php declare(strict_types=1);

namespace Tests\PHPat\integration\test\TestParser;

use PHPat\Selector\Selector;
use PHPat\Test\PHPat;
use PHPat\Test\Rule;
use PHPat\Test\RuleValidatorInterface;
use PHPat\Test\TestExtractorInterface;
use PHPat\Test\TestInstantiatorInterface;
use PHPat\Test\TestParser;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class ParsesTestsTest extends TestCase
{
    public function testClassCollectsMultipleRulesFromFunction(): void
    {
        $testParser = new TestParser(
            new class() implements TestExtractorInterface {
                public function __invoke(): iterable
                {
                    yield new \ReflectionClass(TestClass::class);
                }
            },
            new class() implements RuleValidatorInterface {
                public function validate(Rule $rule): void {}
            },
            new class() implements \PHPat\Test\TestInstantiatorInterface {
                public function instantiate(\ReflectionClass $class): object
                {
                    return $class->newInstance();
                }
            }
        );

        $rule1 = PHPat::rule()->classes(Selector::classname('1'))();
        $rule1->ruleName = TestClass::class.':test_rules_from_iterator:one';

        $rule2 = PHPat::rule()->classes(Selector::classname('2'))();
        $rule2->ruleName = TestClass::class.':test_rules_from_iterator:two';

        $rule3 = PHPat::rule()->classes(Selector::classname('3'))();
        $rule3->ruleName = TestClass::class.':test_rule';

        $rule4 = PHPat::rule()->classes(Selector::classname('4'))();
        $rule4->ruleName = TestClass::class.':test_rule_from_attribute';

        self::assertEquals([
            $rule1,
            $rule2,
            $rule3,
            $rule4,
        ], ($testParser)());
    }
}
