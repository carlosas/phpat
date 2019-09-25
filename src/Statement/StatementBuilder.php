<?php declare(strict_types=1);

namespace PhpAT\Statement;

use PhpAT\File\FileFinder;
use PhpAT\Rule\Rule;
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
        foreach ($this->findFiles($rule->getOrigin(), $rule->getOriginExcluded()) as $file) {
            yield new Statement(
                $this->parseFile($file),
                $rule->getType(),
                $rule->isInverse(),
                $rule->getDestination(),
                $rule->getDestinationExcluded()
            );
        }
    }

    /**
     * @return \SplFileInfo[]
     */
    private function findFiles(array $sources, array $exclude): array
    {
        $found = [];
        foreach ($sources as $source) {
            $found = array_merge($found, $this->fileFinder->findFiles($source, $exclude));
        }

        return $found;
    }

    private function parseFile(\SplFileInfo $file): array
    {
        $code = file_get_contents($file->getPathname());

        return $this->parser->parse($code);
    }
}
