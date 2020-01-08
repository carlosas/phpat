<?php

declare(strict_types=1);

namespace PhpAT\Statement;

use PhpAT\App\Configuration;
use PhpAT\Parser\AstNode;
use PhpAT\Rule\Rule;
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
        $origins = $this->getNamesFromSelectors($rule->getOrigin(), $rule->getOriginExcluded(), $astMap);
        $destinations = $this->getNamesFromSelectors($rule->getDestination(), $rule->getDestinationExcluded(), $astMap);

        if (!empty(Configuration::getSrcIncluded())) {
            $filteredOrigins = [];
            foreach (Configuration::getSrcIncluded() as $checkOnly) {
                $checkOnly = Configuration::getSrcPath() . $checkOnly;
                foreach ($origins as $key => $value) {
                    if (
                        isset($astMap[$value])
                        && $this->normalizePath($checkOnly) == $this->normalizePath($astMap[$value]->getFilePathname())
                    ) {
                        $filteredOrigins[] = $origins[$key];
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
