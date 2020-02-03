<?php

declare(strict_types=1);

namespace PhpAT\Statement;

use PhpAT\App\Configuration;
use PhpAT\Parser\AstNode;
use PhpAT\Parser\ClassLike;
use PhpAT\Parser\FullClassName;
use PhpAT\Rule\Rule;
use PhpAT\Selector\PathSelector;
use PhpAT\Selector\SelectorInterface;
use PhpAT\Selector\SelectorResolver;
use PhpParser\Parser;

class StatementBuilder
{
    /**
     * @var SelectorResolver
     */
    private $selectorResolver;
    /**
     * @var Parser
     */
    private $parser;

    /**
     * StatementBuilder constructor.
     *
     * @param SelectorResolver $selectorResolver
     * @param Parser           $parser
     */
    public function __construct(SelectorResolver $selectorResolver, Parser $parser)
    {
        $this->selectorResolver = $selectorResolver;
        $this->parser = $parser;
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
            $astMap
        );

        foreach ($origins as $originClassName) {
            yield new Statement(
                $originClassName,
                $rule->getAssertion(),
                $rule->isInverse(),
                $destinations
            );
        }
    }

    /**
     * @param array $included
     * @param array $excluded
     * @param array $astMap
     * @return FullClassName[]
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
            foreach ($this->selectorResolver->resolve($excludedSelector, $astMap) as $excludedClassName) {
                foreach ($classNamesToValidate as $key => $value) {
                    if ($excludedClassName == $value) {
                        unset($classNamesToValidate[$key]);
                    }
                }
            }
        }

        if (!empty(Configuration::getSrcIncluded())) {
            $filteredClassNames = [];

            foreach (Configuration::getSrcIncluded() as $inc) {
                echo "-----------------------" . PHP_EOL;
                $resolvedIncludeRow[] = $this->selectorResolver->resolve(new PathSelector($inc), $astMap);
            }

            foreach ($resolvedIncludeRow as $includedClasses) {
                foreach ($includedClasses as $includedClassName) {
                    foreach ($classNamesToValidate as $key => $value) {
                        if (
                            isset($astMap[$value])
                            && $includedClassName == $astMap[$value]->getClassName()
                        ) {
                            $filteredClassNames[] = $classNamesToValidate[$key];
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
    private function selectDestinations(array $included, array $excluded, array $astMap): array
    {
        $classNames = [];
        foreach ($included as $i) {
            $classNames = array_merge($classNames, $this->selectorResolver->resolve($i, $astMap));
        }

        foreach ($excluded as $e) {
            $classNamesToExclude = $this->selectorResolver->resolve($e, $astMap);
            foreach ($classNamesToExclude as $file) {
                foreach ($classNames as $key => $value) {
                    if ($file == $value) {
                        unset($classNames[$key]);
                    }
                }
            }
        }

        return $classNames;
    }
}
