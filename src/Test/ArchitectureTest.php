<?php

declare(strict_types=1);

namespace PhpAT\Test;

use PhpAT\App\Event\FatalErrorEvent;
use PHPAT\EventDispatcher\EventDispatcher;
use PhpAT\Rule\Rule;
use PhpAT\Rule\RuleBuilder;
use PhpAT\Rule\RuleCollection;

abstract class ArchitectureTest
{
    protected $newRule;
    private $eventDispatcher;

    final public function __construct(RuleBuilder $builder, EventDispatcher $eventDispatcher)
    {
        $this->newRule = $builder;
        $this->eventDispatcher = $eventDispatcher;
    }

    final public function __invoke(): RuleCollection
    {
        $rules = new RuleCollection();
        foreach (get_class_methods($this) as $method) {
            if (preg_match('/^(test)([A-Za-z0-9])+$/', $method)) {
                try {
                    $rule = $this->invokeTest($method);
                } catch (\Exception $e) {
                    $this->eventDispatcher->dispatch(new FatalErrorEvent($e->getMessage()));
                    continue;
                }
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
            $message = $method . ' must return an instance of ' . Rule::class . '.';
            if ($rule instanceof RuleBuilder) {
                $message .= ' Did you forget to call build() at the end of your test?';
            }
            throw new \Exception($message);
        }

        return $rule;
    }
}
