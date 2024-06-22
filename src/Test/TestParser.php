<?php declare(strict_types=1);

namespace PHPat\Test;

use PHPat\Test\Attributes\TestRule;
use PHPat\Test\Builder\Rule as RuleBuilder;

class TestParser
{
    /** @var array<Rule> */
    private static array $result = [];
    private TestExtractorInterface $extractor;
    private RuleValidatorInterface $ruleValidator;

    public function __construct(TestExtractorInterface $extractor, RuleValidatorInterface $ruleValidator)
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
        foreach ($tests as $reflected) {
            $classname = $reflected->getName();
            $object = $reflected->newInstanceWithoutConstructor();
            foreach ($reflected->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
                if (
                    method_exists($method, 'getAttributes') && !empty($method->getAttributes(TestRule::class))
                    || preg_match('/^(test)[A-Za-z0-9_\x80-\xff]*/', $method->getName())
                ) {
                    $ruleBuilder = $object->{$method->getName()}();
                    if (is_iterable($ruleBuilder)) {
                        foreach ($ruleBuilder as $name => $rule) {
                            $rules[$classname.':'.$method->getName().':'.$name] = $rule;
                        }
                    } else {
                        $rules[$classname.':'.$method->getName()] = $ruleBuilder;
                    }
                }
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
