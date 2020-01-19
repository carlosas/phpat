<?php

declare(strict_types=1);

namespace PhpAT\Statement;

use PhpAT\App\Configuration;
use PhpAT\Parser\AstNode;
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
        if (!empty(Configuration::getSrcExcluded())) {
            foreach (Configuration::getSrcExcluded() as $exc) {
                $originExcluded[] = new PathSelector($exc);
            }
        }

        $originExcluded = array_merge($rule->getOriginExcluded(), $originExcluded ?? []);
        $origins = $this->getNamesFromSelectors($rule->getOrigin(), $originExcluded, $astMap);
        $destinations = $this->getNamesFromSelectors($rule->getDestination(), $rule->getDestinationExcluded(), $astMap);

        if (!empty(Configuration::getSrcIncluded())) {
            $filteredOrigins = [];
            foreach (Configuration::getSrcIncluded() as $inc) {
                $resolvedIncludeRow[] = $this->getNamesFromSelectors([new PathSelector($inc)], [], $astMap);
            }
            foreach ($resolvedIncludeRow as $includedClasses) {
                foreach ($includedClasses as $includedClassName) {
                    foreach ($origins as $key => $value) {
                        if (
                            isset($astMap[$value])
                            && $includedClassName == $astMap[$value]->getClassName()
                        ) {
                            $filteredOrigins[] = $origins[$key];
                        }
                    }
                }
            }
            $origins = $filteredOrigins;
        }

        foreach ($origins as $originClassName) {
            yield new Statement(
                $originClassName,
                $rule->getType(),
                $rule->isInverse(),
                $destinations
            );
        }
    }

    /**
     * @param SelectorInterface[] $included
     * @param SelectorInterface[] $excluded
     * @param array $astMap
     * @return string[]
     * @throws \Exception
     */
    private function getNamesFromSelectors(array $included, array $excluded, array $astMap): array
    {
        $classNamesToValidate = [];
        foreach ($included as $i) {
            $classNamesToValidate = array_merge($classNamesToValidate, $this->selectorResolver->resolve($i, $astMap));
        }

        foreach ($excluded as $e) {
            $classNamesToExclude = $this->selectorResolver->resolve($e, $astMap);
            foreach ($classNamesToExclude as $file) {
                foreach ($classNamesToValidate as $key => $value) {
                    if ($this->normalizePath($file) == $this->normalizePath($value)) {
                        unset($classNamesToValidate[$key]);
                    }
                }
            }
        }

        return $classNamesToValidate;
    }

    private function normalizePath(string $path): string
    {
        return (\DIRECTORY_SEPARATOR === '\\') ? str_replace('\\', '/', $path) : $path;
    }
}
