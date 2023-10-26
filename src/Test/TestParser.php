<?php declare(strict_types=1);

namespace PHPat\Test;

use PHPat\Test\Attributes\Test;
use PHPat\Test\Builder\Rule as RuleBuilder;

class TestParser
{
    /** @var array<Rule> */
    private static array $result = [];
    private TestExtractor $extractor;
    private RuleValidator $ruleValidator;

    public function __construct(TestExtractor $extractor, RuleValidator $ruleValidator)
    {
        $this->extractor = $extractor;
        $this->ruleValidator = $ruleValidator;
    }

    /**
     * @return array<Rule>
     */
    public function __invoke(): array
    {
        if (empty(self::$result)) {
            self::$result = $this->parse();
        }

        return self::$result;
    }

    /**
     * @return array<Rule>
     */
    private function parse(): array
    {
        $tests = ($this->extractor)();

        $rules = [];
        foreach ($tests as $test) {
            $methods = [];
            $reflected = $test->getNativeReflection();
            foreach ($reflected->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
                if (
                    !empty($method->getAttributes(Test::class))
                    || preg_match('/^(test)[A-Za-z0-9_\x80-\xff]*/', $method->getName())
                ) {
                    $methods[] = $method->getName();
                }
            }

            $object = $reflected->newInstanceWithoutConstructor();
            foreach ($methods as $method) {
                $ruleBuilder = $object->{$method}();
                $rules[$method] = $ruleBuilder;
            }
        }

        return $this->buildRules($rules);
    }

    /**
     * @param  array<string, RuleBuilder> $ruleBuilders
     * @return array<Rule>
     */
    private function buildRules(array $ruleBuilders): array
    {
        $rules = array_map(
            static function (string $ruleName, RuleBuilder $builder): Rule {
                $rule = $builder();
                $rule->ruleName = $ruleName;

                return $rule;
            },
            array_keys($ruleBuilders),
            array_values($ruleBuilders)
        );

        array_walk(
            $rules,
            fn (Rule $rule) => $this->ruleValidator->validate($rule)
        );

        return $rules;
    }
}
