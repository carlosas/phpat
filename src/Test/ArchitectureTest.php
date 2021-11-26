<?php

declare(strict_types=1);

namespace PhpAT\Test;

use PhpAT\App\Event\FatalErrorEvent;
use PhpAT\App\Exception\FatalErrorException;
use PHPAT\EventDispatcher\EventDispatcher;
use PhpAT\Rule\Rule;
use PhpAT\Rule\RuleBuilder;
use PhpAT\Rule\RuleCollection;

abstract class ArchitectureTest implements TestInterface
{
    protected \PhpAT\Rule\RuleBuilder $newRule;
    private \PHPAT\EventDispatcher\EventDispatcher $eventDispatcher;

    final public function __construct(RuleBuilder $builder, EventDispatcher $eventDispatcher)
    {
        $this->newRule = $builder;
        $this->eventDispatcher = $eventDispatcher;
    }

    final public function __invoke(): RuleCollection
    {
        $rules = new RuleCollection();
        foreach (get_class_methods($this) as $method) {
            if (preg_match('/^(test)([_A-Za-z0-9])+$/', $method)) {
                try {
                    $rule = $this->invokeTest($method);
                } catch (\Exception $e) {
                    $this->eventDispatcher->dispatch(new FatalErrorEvent($e->getMessage()));
                    throw new FatalErrorException();
                }
                $rule->setName($this->beautifyMethodName($method));
                $rules->addValue($rule);
            }
        }

        return $rules;
    }

    protected function invokeTest(string $method): Rule
    {
        $rule = $this->$method();

        if (!($rule instanceof Rule)) {
            $message = $method . ' must return an instance of ' . Rule::class . '.';

            $this->eventDispatcher->dispatch(new FatalErrorEvent($message));
            throw new FatalErrorException();
        }

        if ($rule->getAssertion() === null) {
            $message = $method
                . ' is not properly defined. Please make sure that you define one of the assertion methods'
                . '(e.g. `mustImplement` or `mustNotDependOn`) to declare the assertion of the rule.';

            $this->eventDispatcher->dispatch(new FatalErrorEvent($message));
            throw new FatalErrorException();
        }

        return $rule;
    }

    private function beautifyMethodName(string $methodName): string
    {
        return ucfirst(
            ltrim(
                str_replace(
                    '_',
                    ' ',
                    preg_replace('/(?<!\ )[A-Z]/', '_$0', $methodName)
                ),
                'test '
            )
        );
    }
}
