<?php declare(strict_types=1);

namespace PhpAT\Statement;

use PhpAT\File\FileFinder;
use PhpAT\Rule\Rule;
use PhpAT\Rule\RuleCollection;
use PhpParser\Parser;

class StatementBuilder
{
    private $fileFinder;
    private $parser;

    public function __construct(FileFinder $fileFinder, Parser $parser)
    {
        $this->fileFinder = $fileFinder;
        $this->parser = $parser;
    }

    public function build(Rule $rule): \Generator
    {
        foreach ($this->findFiles($rule->getSource(), $rule->getExcluded()) as $file) {
            $errorMsg = $file->getPathname()
                . ' does not satisfy the rule'
                . PHP_EOL;

            yield new Statement(
                $this->parseFile($file),
                $rule->getType(),
                $rule->getParams(),
                $rule->isInverse(),
                $errorMsg
            );
        }
    }

    /**
     * @return \SplFileInfo[]
     */
    private function findFiles(string $source, array $exclude): array
    {
        return $this->fileFinder->findFiles($source, $exclude);
    }

    private function parseFile(\SplFileInfo $file): array
    {
        $code = file_get_contents($file->getPathname());

        return $this->parser->parse($code);
    }
}
