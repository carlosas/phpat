<?php

declare(strict_types=1);

namespace PHPat\Test;

use PHPat\Test\Builder\Rule;
use ReflectionMethod;

class TestParser
{
    /** @var array<RelationRule> */
    private static array $result = [];
    private TestExtractor $extractor;
    private RuleValidator $ruleValidator;

    public function __construct(TestExtractor $extractor, RuleValidator $ruleValidator)
    {
        $this->extractor = $extractor;
        $this->ruleValidator = $ruleValidator;
    }

    /**
     * @return array<RelationRule>
     */
    public function __invoke(): array
    {
        if (empty(self::$result)) {
            self::$result = $this->parse();
        }

        return self::$result;
    }

    /**
     * @return array<RelationRule>
     */
    private function parse(): array
    {
        $tests = ($this->extractor)();

        $rules = [];
        foreach ($tests as $test) {
            $methods   = [];
            $reflected = $test->getNativeReflection();
            foreach ($reflected->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
                if (
                    preg_match('/^(test)[A-Za-z0-9_\x80-\xff]*/', $method->getName())
                    && $method->getReturnType()->getName() === Rule::class
                ) {
                    $methods[] = $method->getName();
                }
            }

            $object = $reflected->newInstanceWithoutConstructor();
            foreach ($methods as $method) {
                $rules[] = $object->{$method}();
            }
        }

        return $this->buildRules($rules);
    }

    /**
     * @param array<Rule> $rules
     * @return array<RelationRule>
     */
    private function buildRules(array $rules): array
    {
        $rules = array_map(
            static fn (Rule $rule): RelationRule => $rule->return(),
            $rules
        );

        array_walk(
            $rules,
            fn (RelationRule $rule) => $this->ruleValidator->validate($rule)
        );

        return $rules;
    }
}
