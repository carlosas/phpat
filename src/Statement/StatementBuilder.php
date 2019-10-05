<?php

declare(strict_types=1);

namespace PhpAT\Statement;

use PhpAT\File\FileFinder;
use PhpAT\Rule\Rule;
use PhpAT\Selector\SelectorResolver;
use PhpParser\Parser;

class StatementBuilder
{
    /** @var SelectorResolver */
    private $selectorResolver;
    /** @var Parser */
    private $parser;

    /**
     * StatementBuilder constructor.
     * @param SelectorResolver $selectorResolver
     * @param Parser $parser
     */
    public function __construct(SelectorResolver $selectorResolver, Parser $parser)
    {
        $this->selectorResolver = $selectorResolver;
        $this->parser = $parser;
    }

    /**
     * @param Rule $rule
     * @return \Generator
     */
    public function build(Rule $rule): \Generator
    {
        $destinations = $this->selectFiles($rule->getDestination(), $rule->getDestinationExcluded());
        foreach ($this->selectFiles($rule->getOrigin(), $rule->getOriginExcluded()) as $file) {
            yield new Statement(
                $this->parseFile($file),
                $rule->getType(),
                $rule->isInverse(),
                $destinations
            );
        }
    }

    /**
     * @param array $included
     * @param array $excluded
     * @return \SplFileInfo[]
     */
    private function selectFiles(array $included, array $excluded): array
    {
        $filesToValidate = [];
        foreach ($included as $i) {
            $filesToValidate = array_merge($filesToValidate, $this->selectorResolver->resolve($i));
        }

        foreach ($excluded as $e) {
            $filesToExclude = $this->selectorResolver->resolve($e);
            foreach ($filesToExclude as $file) {
                $keys = array_keys($filesToValidate, $file);
                if (!empty($keys)) {
                    foreach ($keys as $key) {
                        unset($filesToValidate[$key]);
                    }
                }
            }
        }

        return $filesToValidate;
    }

    /**
     * @param \SplFileInfo $file
     * @return array
     */
    private function parseFile(\SplFileInfo $file): array
    {
        $code = file_get_contents($file->getPathname());

        return $this->parser->parse($code);
    }
}
