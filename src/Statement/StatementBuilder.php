<?php

declare(strict_types=1);

namespace PhpAT\Statement;

use PhpAT\App\Configuration;
use PhpAT\App\Event\WarningEvent;
use PhpAT\Parser\AstNode;
use PhpAT\Parser\ClassLike;
use PhpAT\Parser\FullClassName;
use PhpAT\Parser\RegexClassName;
use PhpAT\Rule\Assertion\AbstractAssertion;
use PhpAT\Rule\Rule;
use PhpAT\Selector\PathSelector;
use PhpAT\Selector\SelectorInterface;
use PhpAT\Selector\SelectorResolver;
use Psr\EventDispatcher\EventDispatcherInterface;

class StatementBuilder
{
    /**
     * @var SelectorResolver
     */
    private $selectorResolver;
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * StatementBuilder constructor.
     * @param SelectorResolver         $selectorResolver
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(SelectorResolver $selectorResolver, EventDispatcherInterface $eventDispatcher)
    {
        $this->selectorResolver = $selectorResolver;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param Rule  $rule
     * @param AstNode[] $astMap
     * @return \Generator
     * @throws \Exception
     */
    public function build(Rule $rule, array $astMap): \Generator
    {
        $origins = $this->selectOrigins($rule->getOrigin(), $rule->getOriginExcluded(), $astMap);
        $destinations = $this->selectDestinations(
            $rule->getDestination(),
            $rule->getDestinationExcluded(),
            $rule->getAssertion(),
            $astMap
        );

        foreach ($origins as $originClassName) {
            yield new Statement(
                $originClassName,
                $rule->getAssertion(),
                $destinations
            );
        }
    }

    /**
     * @param array $includedInRule
     * @param array $excludedInRule
     * @param array $astMap
     * @return ClassLike[]
     * @throws \Exception
     */
    private function selectOrigins(array $includedInRule, array $excludedInRule, array $astMap): array
    {
        $classNamesToValidate = [];

        foreach ($includedInRule as $i) {
            $classNamesToValidate = array_merge($classNamesToValidate, $this->selectorResolver->resolve($i, $astMap));
        }

        foreach (Configuration::getSrcExcluded() as $exc) {
            $excludedInConfig[] = new PathSelector($exc);
        }
        $excludedSelectors = array_merge($excludedInRule, $excludedInConfig ?? []);
        foreach ($excludedSelectors as $excludedSelector) {
            /** @var ClassLike $excludedClassName */
            foreach ($this->selectorResolver->resolve($excludedSelector, $astMap) as $excludedClassName) {
                /** @var ClassLike $value */
                foreach ($classNamesToValidate as $key => $value) {
                    if ($value->matches($excludedClassName->toString())) {
                        unset($classNamesToValidate[$key]);
                    }
                }
            }
        }

        if (!empty(Configuration::getSrcIncluded())) {
            $filteredClassNames = [];

            foreach (Configuration::getSrcIncluded() as $inc) {
                $resolvedIncludeRow[] = $this->selectorResolver->resolve(new PathSelector($inc), $astMap);
            }
            foreach ($resolvedIncludeRow as $includedClasses) {
                /** @var ClassLike $includedClassName */
                foreach ($includedClasses as $includedClassName) {
                    /** @var ClassLike $value */
                    foreach ($classNamesToValidate as $key => $value) {
                        if (
                            isset($astMap[$value->toString()])
                            && $includedClassName->matches($astMap[$value->toString()]->getClassName())
                        ) {
                            $filteredClassNames[$key] = $value;
                        }
                    }
                }
            }
            $classNamesToValidate = $filteredClassNames;
        }

        return $classNamesToValidate;
    }

    /**
     * @param SelectorInterface[] $included
     * @param SelectorInterface[] $excluded
     * @param array $astMap
     * @return ClassLike[]
     * @throws \Exception
     */
    private function selectDestinations(
        array $included,
        array $excluded,
        AbstractAssertion $assertion,
        array $astMap
    ): array {
        $classLikeNames = [];
        foreach ($included as $i) {
            if ($this->isRegex($i->getParameter()) && $assertion->acceptsRegex() === false) {
                $assertionName = substr(get_class($assertion), strrpos(get_class($assertion), '\\') + 1);
                $message = '(' . $assertionName . ') Using expresion as a destination selector. Ignoring: '
                    . $i->getParameter();
                $this->eventDispatcher->dispatch(new WarningEvent($message));
                continue;
            }

            $classLikeNames = array_merge($classLikeNames, $this->selectorResolver->resolve($i, $astMap));
        }

        foreach ($excluded as $e) {
            $classNamesToExclude = $this->selectorResolver->resolve($e, $astMap);
            foreach ($classNamesToExclude as $file) {
                foreach ($classLikeNames as $key => $value) {
                    if ($file == $value) {
                        unset($classLikeNames[$key]);
                    }
                }
            }
        }

        return array_values($classLikeNames);
    }

    private function isRegex($str): bool
    {
        return strpos($str, '*') !== false;
    }
}
