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
            /** @var \SplFileInfo $file */
            foreach ($filesToExclude as $file) {
                foreach ($filesToValidate as $key => $value) {
                    if ($this->normalizePath($file->getPathname()) == $this->normalizePath($value->getPathname())) {
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

    private function normalizePath(string $path): string
    {
        return ('\\' === \DIRECTORY_SEPARATOR) ? str_replace('\\', '/', $path) : $path;
    }
}
