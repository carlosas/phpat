<?php declare(strict_types=1);

namespace Tests\PHPat\integration\test\TestParser;

use PHPat\Selector\Selector;
use PHPat\Test\PHPat;
use PHPat\Test\Rule;
use PHPat\Test\RuleValidatorInterface;
use PHPat\Test\TestExtractorInterface;
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
        $param = random_int(1, 100);

        $testParser = new TestParser(
            new class($param) implements TestExtractorInterface {
                public function __construct(private int $param) {}

                public function __invoke(): iterable
                {
                    yield [new \ReflectionClass(TestClass::class), new TestClass($this->param)];
                }
            },
            new class() implements RuleValidatorInterface {
                public function validate(Rule $rule): void {}
            },
        );

        $rule1 = PHPat::rule()->classes(Selector::classname('1'))();
        $rule1->ruleName = TestClass::class.':test_rules_from_iterator:one';

        $rule2 = PHPat::rule()->classes(Selector::classname('2'))();
        $rule2->ruleName = TestClass::class.':test_rules_from_iterator:two';

        $rule3 = PHPat::rule()->classes(Selector::classname('3'))();
        $rule3->ruleName = TestClass::class.':test_rule';

        $rule4 = PHPat::rule()->classes(Selector::classname('4'))();
        $rule4->ruleName = TestClass::class.':test_rule_from_attribute';

        $rule5 = PHPat::rule()->classes(Selector::classname((string) $param))();
        $rule5->ruleName = TestClass::class.':test_configurable_rule';

        self::assertEquals([
            $rule1,
            $rule2,
            $rule3,
            $rule4,
            $rule5,
        ], ($testParser)());
    }
}
