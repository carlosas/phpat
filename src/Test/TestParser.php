<?php

declare(strict_types=1);

namespace PHPat\Test;

use PHPat\Test\Builder\Rule as RuleBuilder;
use PHPat\Test\Builder\RuleBuilderWithName;
use ReflectionMethod;

class TestParser
{
    /** @var array<RuleWithName<RelationRule>> */
    private static array $result = [];
    private TestExtractor $extractor;
    private RuleValidator $ruleValidator;

    public function __construct(TestExtractor $extractor, RuleValidator $ruleValidator)
    {
        $this->extractor     = $extractor;
        $this->ruleValidator = $ruleValidator;
    }

    /**
     * @return array<RuleWithName<RelationRule>>
     */
    public function __invoke(): array
    {
        if (empty(self::$result)) {
            self::$result = $this->parse();
        }

        return self::$result;
    }

    /**
     * @return array<RuleWithName<RelationRule>>
     */
    private function parse(): array
    {
        $tests = ($this->extractor)();

        $rules = [];
        foreach ($tests as $test) {
            $methods   = [];
            $reflected = $test->getNativeReflection();
            foreach ($reflected->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
                if (preg_match('/^(test)[A-Za-z0-9_\x80-\xff]*/', $method->getName())
                ) {
                    $methods[] = $method->getName();
                }
            }


            $object = $reflected->newInstanceWithoutConstructor();
            foreach ($methods as $method) {
                $rule = $object->{$method}();
                if ($rule instanceof RuleBuilder) {
                    $rules[] = new RuleBuilderWithName(new TestName($method), $rule);
                }
            }
        }

        return $this->buildRules($rules);
    }

    /**
     * @param array<RuleBuilderWithName> $ruleBuilders
     * @return array<RuleWithName<RelationRule>>
     */
    private function buildRules(array $ruleBuilders): array
    {
        $rules = array_map(
            static fn (RuleBuilderWithName $rule): RuleWithName => new RuleWithName($rule->getTestName(), ($rule->getRuleBuilder())()),
            $ruleBuilders
        );

        array_walk(
            $rules,
            fn (RuleWithName $rule) => $this->ruleValidator->validate($rule)
        );

        return $rules;
    }
}
