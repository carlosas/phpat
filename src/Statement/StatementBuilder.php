<?php

declare(strict_types=1);

namespace PhpAT\Statement;

use PhpAT\App\Configuration;
use PhpAT\App\Event\WarningEvent;
use PhpAT\Parser\Ast\AstNode;
use PhpAT\Parser\Ast\ReferenceMap;
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
     * @param ReferenceMap $map
     * @return \Generator
     * @throws \Exception
     */
    public function build(Rule $rule, ReferenceMap $map): \Generator
    {
        $origins = $this->selectOrigins($rule->getOrigin(), $rule->getOriginExcluded(), $map);
        $destinations = $this->selectDestinations(
            $rule->getDestination(),
            $rule->getAssertion(),
            $map
        );
        $excludedDestinations = $this->selectDestinations(
            $rule->getDestinationExcluded(),
            $rule->getAssertion(),
            $map
        );

        foreach ($origins as $originClassName) {
            yield new Statement(
                $originClassName,
                $rule->getAssertion(),
                $destinations,
                $excludedDestinations
            );
        }
    }

    /**
     * @param array $includedInRule
     * @param array $excludedInRule
     * @param ReferenceMap $map
     * @return ClassLike[]
     * @throws \Exception
     */
    private function selectOrigins(array $includedInRule, array $excludedInRule, ReferenceMap $map): array
    {
        $classNamesToValidate = [];

        foreach ($includedInRule as $i) {
            $classNamesToValidate = array_merge($classNamesToValidate, $this->selectorResolver->resolve($i, $map));
        }

        foreach (Configuration::getSrcExcluded() as $exc) {
            $excludedInConfig[] = new PathSelector($exc);
        }
        $excludedSelectors = array_merge($excludedInRule, $excludedInConfig ?? []);
        foreach ($excludedSelectors as $excludedSelector) {
            /** @var ClassLike $excludedClassName */
            foreach ($this->selectorResolver->resolve($excludedSelector, $map) as $excludedClassName) {
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
                $resolvedIncludeRow[] = $this->selectorResolver->resolve(new PathSelector($inc), $map);
            }
            foreach ($resolvedIncludeRow as $includedClasses) {
                /** @var ClassLike $includedClassName */
                foreach ($includedClasses as $includedClassName) {
                    /** @var ClassLike $value */
                    foreach ($classNamesToValidate as $key => $value) {
                        if (
                            isset($map->getSrcNodes()[$value->toString()])
                            && $includedClassName->matches($map->getSrcNodes()[$value->toString()]->getClassName())
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
     * @param SelectorInterface[] $selectors
     * @param AbstractAssertion $assertion
     * @param ReferenceMap $map
     * @return ClassLike[]
     * @throws \Exception
     */
    private function selectDestinations(
        array $selectors,
        AbstractAssertion $assertion,
        ReferenceMap $map
    ): array {
        $classLikeNames = [];
        foreach ($selectors as $s) {
            if ($this->isRegex($s->getParameter()) && $assertion->acceptsRegex() === false) {
                $assertionName = substr(get_class($assertion), strrpos(get_class($assertion), '\\') + 1);
                $message = $assertionName . ' can not assert regex selectors. Ignoring: ' . $s->getParameter();
                $this->eventDispatcher->dispatch(new WarningEvent($message));
                continue;
            }

            $classLikeNames = array_merge($classLikeNames, $this->selectorResolver->resolve($s, $map));
        }

        return array_values($classLikeNames);
    }

    private function isRegex($str): bool
    {
        return strpos($str, '*') !== false;
    }
}
