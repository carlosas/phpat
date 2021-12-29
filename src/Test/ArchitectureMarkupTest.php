<?php

namespace PhpAT\Test;

use PhpAT\App\Event\FatalErrorEvent;
use PhpAT\App\Exception\FatalErrorException;
use PHPAT\EventDispatcher\EventDispatcher;
use PhpAT\Rule\Rule;
use PhpAT\Rule\RuleBuilder;
use PhpAT\Rule\RuleCollection;
use Psr\EventDispatcher\EventDispatcherInterface;

class ArchitectureMarkupTest implements TestInterface
{
    protected RuleBuilder $newRule;
    private EventDispatcherInterface $eventDispatcher;
    private array $methods;

    final public function __construct(array $methods, RuleBuilder $builder, EventDispatcherInterface $eventDispatcher)
    {
        $this->newRule         = $builder;
        $this->eventDispatcher = $eventDispatcher;
        $this->methods         = $methods;
    }

    final public function __invoke(): RuleCollection
    {
        $rules = new RuleCollection();
        foreach ($this->methods as $method) {
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
        $rule = call_user_func($this->$method);

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
}
