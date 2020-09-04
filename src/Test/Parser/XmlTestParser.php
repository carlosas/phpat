<?php

namespace PhpAT\Test\Parser;

use PhpAT\App\Event\FatalErrorEvent;
use PhpAT\App\Exception\FatalErrorException;
use PHPAT\EventDispatcher\EventDispatcher;
use PhpAT\Rule\Rule;
use PhpAT\Rule\RuleBuilder;
use PhpAT\Selector\Selector;
use PhpAT\Selector\SelectorInterface;
use PhpAT\Test\ArchitectureMarkupTest;
use PhpAT\Test\TestInterface;

class XmlTestParser
{
    private $ruleBuilder;
    private $eventDispatcher;

    public function __construct(RuleBuilder $ruleBuilder, EventDispatcher $eventDispatcher)
    {
        $this->ruleBuilder = $ruleBuilder;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function parseFile(string $pathToFile): ArchitectureMarkupTest
    {
        $fileContents = simplexml_load_file($pathToFile);
        $rules = $fileContents->rule;
        $methods = [];
        foreach ($rules as $rule) {
            $methods[] = trim((string) $rule['name']);
        }

        $class = new ArchitectureMarkupTest(
            $methods,
            $this->ruleBuilder,
            $this->eventDispatcher
        );

        foreach ($rules as $rule) {
            $parsedRule = $this->parseRule($rule);
            $class->{trim((string) $rule['name'])} = function () use ($parsedRule) {
                return $parsedRule;
            };
        }
        return $class;
    }

    private function parseRule(\SimpleXMLElement $rule): Rule
    {
        if ($rule->classes->count() != 2) {
            $this->eventDispatcher->dispatch(
                new FatalErrorEvent('Rule must have 2 <classes>')
            );
            throw new FatalErrorException();
        }

        if ($rule->assert->count() != 1) {
            $this->eventDispatcher->dispatch(
                new FatalErrorEvent('Rule must have 1 <assert>')
            );
            throw new FatalErrorException();
        }

        $this->buildClasses($rule->classes[0]);
        if ($rule->excluding[0]) {
            $this->buildExcluding($rule->excluding[0]);
        }
        $this->buildAssertion(trim((string) $rule->assert));
        $this->buildClasses($rule->classes[1]);
        if ($rule->excluding[1]) {
            $this->buildExcluding($rule->excluding[1]);
        }

        return $this->ruleBuilder->build();
    }

    private function buildClasses(\SimpleXMLElement $options): void
    {
        $selectors = $options->selector;

        foreach ($selectors as $selector) {
            $builtSelector = $this->buildSelector(trim((string) $selector['type']), trim((string) $selector));
            $this->ruleBuilder->classesThat($builtSelector);
        }
    }

    private function buildExcluding(\SimpleXMLElement $options): void
    {
        $selectors = $options->selector;

        foreach ($selectors as $selector) {
            $builtSelector = $this->buildSelector(trim((string) $selector['type']), trim((string) $selector));
            $this->ruleBuilder->excludingClassesThat($builtSelector);
        }
    }

    private function buildAssertion(string $assertion): void
    {
        $reflector = new \ReflectionClass($this->ruleBuilder);
        $methodReflector = $reflector->getMethod($assertion);
        if ($methodReflector->isPublic() && $methodReflector->getNumberOfParameters() === 0) {
            $this->ruleBuilder->$assertion();
        } else {
            $this->eventDispatcher->dispatch(
                new FatalErrorEvent('Assertion ' . $assertion . 'can not have parameters')
            );
            throw new FatalErrorException();
        }
    }

    private function buildSelector(string $selector, string $selectorRule): SelectorInterface
    {
        $reflector = new \ReflectionClass(Selector::class);
        $methodReflector = $reflector->getMethod($selector);
        if ($methodReflector->isStatic() && $methodReflector->isPublic()) {
            return Selector::$selector($selectorRule);
        } else {
            $this->eventDispatcher->dispatch(new FatalErrorEvent('Selector ' . $selector . 'is not a static method'));
            throw new FatalErrorException();
        }
    }
}
