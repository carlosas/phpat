<?php declare(strict_types=1);

namespace PHPArchiTest\Statement;

use PHPArchiTest\File\FileFinder;
use PHPArchiTest\Parser\Parser;
use PHPArchiTest\Rule\RuleCollection;
use Roave\BetterReflection\Reflection\ReflectionClass;

class StatementBuilder
{
    private $fileFinder;
    private $parser;

    public function __construct(FileFinder $fileFinder, Parser $parser)
    {
        $this->fileFinder = $fileFinder;
        $this->parser = $parser;
    }

    public function build(RuleCollection $rules): \Generator
    {
        foreach ($rules->getValues() as $rule) {
            /** @var ReflectionClass $origin */
            foreach ($this->findAndParseOriginFiles($rule->getOrigin(), $rule->getOriginExcluded()) as $origin) {
                foreach ($this->findAndParseDestinationFiles($rule->getDestination(), $rule->getDestinationExcluded()) as $destination) {
                    if ($origin === $destination) {
                        continue;
                    }
                    yield new Statement($origin, $rule->getType(), $destination, $rule->isInverse(), $rule->getName());
                }
            }
        }
    }

    private function findAndParseOriginFiles(string $source, array $exclude): \Generator
    {
        $filesFound = $this->fileFinder->findOrigin($source, $exclude);
        foreach ($filesFound as $file) {
            foreach ($this->parser->parseFile($file) as $class) {
                yield $class;
            }
        }
    }

    private function findAndParseDestinationFiles(string $source, array $exclude): \Generator
    {
        $filesFound = $this->fileFinder->findDestination($source, $exclude);
        foreach ($filesFound as $file) {
            foreach ($this->parser->parseFile($file) as $class) {
                yield $class;
            }
        }
    }
}
