<?php

namespace PhpAT\Test\Parser;

use PhpAT\App\Event\FatalErrorEvent;
use PHPAT\EventDispatcher\EventDispatcher;
use PhpAT\Rule\Rule;
use PhpAT\Rule\RuleBuilder;
use PhpAT\Selector\Selector;
use PhpAT\Selector\SelectorInterface;
use PhpAT\Test\ArchitectureMarkupTest;
use Symfony\Component\Yaml\Yaml;

class YamlTestParser
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
        $fileContents = Yaml::parse(file_get_contents($pathToFile));
        $rules = $fileContents['rules'];
        $methods = array_keys($rules);
        $class = new ArchitectureMarkupTest(
            $methods,
            $this->ruleBuilder,
            $this->eventDispatcher
        );

        foreach ($rules as $key => $rule) {
            $parsedRule = $this->parseRule($rule);
            $class->{$key} = function () use ($parsedRule) {
                return $parsedRule;
            };
        }
        return $class;
    }

    private function parseRule(array $rule): Rule
    {
        foreach ($rule as $statement) {
            foreach ($statement as $statementName => $options) {
                switch ($statementName) {
                    case 'classes':
                        $this->buildClasses($options);
                        break;
                    case 'excluding':
                        $this->buildExcluding($options);
                        break;
                    case 'assert':
                        $this->buildAssertion($options);
                        break;
                    default:
                        $this->eventDispatcher->dispatch(
                            new FatalErrorEvent('Statement ' . $statementName . 'is not implemented')
                        );
                        break;
                }
            }
        }
        return $this->ruleBuilder->build();
    }

    private function buildClasses(array $options): void
    {
        foreach ($options as $index => $option) {
            foreach ($option as $selector => $selectorRule) {
                $builtSelector = $this->buildSelector($selector, $selectorRule);
                $index === 0 ?
                    $this->ruleBuilder->classesThat($builtSelector) :
                    $this->ruleBuilder->andClassesThat($builtSelector);
            }
        }
    }

    private function buildExcluding(array $options): void
    {
        foreach ($options as $index => $option) {
            foreach ($option as $selector => $selectorRule) {
                $builtSelector = $this->buildSelector($selector, $selectorRule);
                $index === 0 ?
                    $this->ruleBuilder->excludingClassesThat($builtSelector) :
                    $this->ruleBuilder->andExcludingClassesThat($builtSelector);
            }
        }
    }

    private function buildAssertion(string $assertion): void
    {
        try {
            $reflector = new() \ReflectionClass($this->ruleBuilder);
            $methodReflector = $reflector->getMethod($assertion);
            if ($methodReflector->isPublic() && $methodReflector->getNumberOfParameters() === 0) {
                $this->ruleBuilder->$assertion();
            } else {
                throw new() \Exception('Assertion ' . $assertion . 'can not have parameters');
            }
        } catch (\Exception $e) {
            $this->eventDispatcher->dispatch(new FatalErrorEvent($e->getMessage()));
            throw $e;
        }
    }

    private function buildSelector(string $selector, string $selectorRule): SelectorInterface
    {
        $reflector = new() \ReflectionClass(Selector::class);
        try {
            $methodReflector = $reflector->getMethod($selector);
            if ($methodReflector->isStatic() && $methodReflector->isPublic()) {
                return Selector::$selector($selectorRule);
            } else {
                throw new() \Exception('Selector ' . $selector . 'is not a static method');
            }
        } catch (\Exception $e) {
            $this->eventDispatcher->dispatch(new FatalErrorEvent($e->getMessage()));
            throw $e;
        }
    }
}
