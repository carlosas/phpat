<?php declare(strict_types=1);

namespace PhpAT\Statement;

use PhpAT\App\Configuration;
use PhpAT\Rule\Rule;
use PhpAT\Selector\SelectorInterface;
use PhpAT\Selector\SelectorResolver;
use PhpParser\Parser;

class StatementBuilder
{
    /** @var SelectorResolver */
    private $selectorResolver;

    /** @var Parser */
    private $parser;

    /** @var Configuration */
    private $configuration;

    public function __construct(SelectorResolver $selectorResolver, Parser $parser, Configuration $configuration)
    {
        $this->selectorResolver = $selectorResolver;
        $this->parser = $parser;
        $this->configuration = $configuration;
    }

    public function build(Rule $rule): \Generator
    {
        $origins = $this->selectFiles($rule->getOrigin(), $rule->getOriginExcluded());
        $destinations = $this->selectFiles($rule->getDestination(), $rule->getDestinationExcluded());

        if (!empty($this->configuration->getSrcIncluded())) {
            $filteredOrigins = [];
            foreach ($this->configuration->getSrcIncluded() as $checkOnly) {
                $checkOnly = $this->configuration->getSrcPath() . $checkOnly;
                foreach ($origins as $key => $value) {
                    if ($this->normalizePath($checkOnly) == $this->normalizePath($value->getPathname())) {
                        $filteredOrigins[] = $origins[$key];
                    }
                }
            }
            $origins = $filteredOrigins;
        }

        foreach ($origins as $file) {
            yield new Statement(
                $this->parseFile($file),
                $rule->getType(),
                $rule->isInverse(),
                $destinations
            );
        }
    }

    /**
     * @param SelectorInterface[] $included
     * @param SelectorInterface[] $excluded
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
            /**
             * @var \SplFileInfo $file
             */
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
     * FIXME `Parser#parse` returns Stmt[]|null but this return type is only array. If `null` is returned this method will break.
     * @param  \SplFileInfo $file
     * @return array
     */
    private function parseFile(\SplFileInfo $file): array
    {
        $code = file_get_contents($file->getPathname());

        return $this->parser->parse($code);
    }

    private function normalizePath(string $path): string
    {
        return (\DIRECTORY_SEPARATOR === '\\') ? str_replace('\\', '/', $path) : $path;
    }
}
