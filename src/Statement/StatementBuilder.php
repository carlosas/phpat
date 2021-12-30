<?php

declare(strict_types=1);

namespace PhpAT\Statement;

use PhpAT\App\Configuration;
use PhpAT\App\Event\WarningEvent;
use PhpAT\Parser\Ast\ClassLike;
use PhpAT\Parser\Ast\FullClassName;
use PhpAT\Parser\Ast\ReferenceMap;
use PhpAT\Parser\Ast\RegexClassName;
use PhpAT\Parser\Ast\SrcNode;
use PhpAT\Rule\Assertion\AbstractAssertion;
use PhpAT\Rule\Rule;
use PhpAT\Selector\PathSelector;
use PhpAT\Selector\SelectorInterface;
use PhpAT\Selector\SelectorResolver;
use Psr\EventDispatcher\EventDispatcherInterface;

class StatementBuilder
{
    private SelectorResolver $selectorResolver;
    private EventDispatcherInterface $eventDispatcher;
    private Configuration $configuration;

    /**
     * StatementBuilder constructor.
     */
    public function __construct(
        SelectorResolver $selectorResolver,
        EventDispatcherInterface $eventDispatcher,
        Configuration $configuration
    ) {
        $this->selectorResolver = $selectorResolver;
        $this->eventDispatcher  = $eventDispatcher;
        $this->configuration    = $configuration;
    }

    /**
     * @throws \Exception
     */
    public function build(Rule $rule, ReferenceMap $map): \Generator
    {
        $origins      = $this->selectOrigins($rule->getOrigin(), $rule->getOriginExcluded(), $map);
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
     * @throws \Exception
     * @return array<ClassLike>
     */
    private function selectOrigins(array $includedInRule, array $excludedInRule, ReferenceMap $map): array
    {
        $classNamesToValidate = [];

        foreach ($includedInRule as $i) {
            $classNamesToValidate = array_merge($classNamesToValidate, $this->selectorResolver->resolve($i, $map));
        }

        //foreach ($this->configuration->getParserExclude() as $exc) {
        //    $excludedInConfig[] = new PathSelector($exc);
        //}
        //$excludedSelectors = array_merge($excludedInRule, $excludedInConfig ?? []);
        foreach ($excludedInRule as $excludedSelector) {
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

//        if (!empty($this->configuration->getSrcIncluded())) {
//            $resolvedIncludeRow = [];
//            foreach ($this->configuration->getSrcIncluded() as $inc) {
//                $resolvedIncludeRow[] = $this->selectorResolver->resolve(new PathSelector($inc), $map);
//            }
//            $filteredClassNames = [];
//            foreach ($resolvedIncludeRow as $includedClasses) {
//                /** @var ClassLike $includedClassName */
//                foreach ($includedClasses as $includedClassName) {
//                    /** @var ClassLike $value */
//                    foreach ($classNamesToValidate as $key => $value) {
//                        if (
//                            isset($map->getSrcNodes()[$value->toString()])
//                            && $includedClassName->matches($map->getSrcNodes()[$value->toString()]->getClassName())
//                        ) {
//                            $filteredClassNames[$key] = $value;
//                        }
//                    }
//                }
//            }
//            $classNamesToValidate = $filteredClassNames;
//        }

        $classNamesToValidate = $this->removeRegexClassNames($classNamesToValidate);

        return $classNamesToValidate;
    }

    /**
     * @param array<SelectorInterface> $selectors
     * @throws \Exception
     * @return array<ClassLike>
     */
    private function selectDestinations(
        array $selectors,
        AbstractAssertion $assertion,
        ReferenceMap $map
    ): array {
        $classLikeNames = [];
        foreach ($selectors as $s) {
            if ($this->isRegex($s->getParameter()) && !$assertion->acceptsRegex()) {
                $assertionName = substr(get_class($assertion), strrpos(get_class($assertion), '\\') + 1);
                $message       = $assertionName . ' can not assert regex selectors. Ignoring: ' . $s->getParameter();
                $this->eventDispatcher->dispatch(new WarningEvent($message));
                continue;
            }

            $classLikeNames = array_merge($classLikeNames, $this->selectorResolver->resolve($s, $map));
        }

        return array_values($classLikeNames);
    }

    private function removeRegexClassNames(array $classNames): array
    {
        return array_filter(
            $classNames,
            fn (ClassLike $c) => !$this->isRegex($c->toString())
        );
    }

    private function isRegex(string $str): bool
    {
        return strpos($str, '*') !== false;
    }
}
