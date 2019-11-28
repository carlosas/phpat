<?php

declare(strict_types=1);

namespace PhpAT\Test;

use PhpAT\Rule\Rule;
use PhpAT\Rule\RuleBuilder;
use PhpAT\Rule\RuleCollection;

abstract class ArchitectureTest
{
    protected $newRule;

    final public function __construct(RuleBuilder $builder)
    {
        $this->newRule = $builder;
    }

    final public function __invoke(): RuleCollection
    {
        $rules = new RuleCollection();
        foreach (get_class_methods($this) as $method) {
            if (preg_match('/^(test)([A-Za-z0-9])+$/', $method)) {
                $rule = $this->invokeTest($method);
                $rule->setName(ltrim(preg_replace('/(?<!\ )[A-Z]/', ' $0', $method), 'test '));
                $rules->addValue($rule);
            }
        }

        return $rules;
    }

    private function invokeTest(string $method): Rule
    {
        $rule = $this->$method();

        if (!($rule instanceof Rule)) {
            $message = sprintf(
                'An architecture test must return an instance of %s.',
                Rule::class
            );
            if ($rule instanceof RuleBuilder) {
                $message .= ' Did you forget to call ->build() at the end of your test?';
            }
            throw new \Exception($message);
        }

        return $rule;
    }
}
