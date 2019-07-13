<?php declare(strict_types=1);

namespace PHPArchiTest\Test;

use PHPArchiTest\Rule\Rule;
use PHPArchiTest\Rule\RuleBuilder;
use PHPArchiTest\Rule\RuleCollection;

abstract class ArchiTest
{
    protected $newRule;

    final public function __construct() {
        $this->newRule = new RuleBuilder();
    }

    /**
     * @throws \ReflectionException
     */
    final public function __invoke(): RuleCollection
    {
        $rules = new RuleCollection();
        foreach (get_class_methods($this) as $method) {
            if (preg_match('/^(test)([A-Za-z0-9])+$/', $method)) {
                /** @var Rule $rule */
                $rule = $this->$method();
                $rule->setName(ltrim(preg_replace('/(?<!\ )[A-Z]/', ' $0', $method), 'test '));
                $rules->addValue($rule);
            }
        }

        return $rules;
    }
}
