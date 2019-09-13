<?php declare(strict_types=1);

namespace PhpAT\Rule\Type;

use PhpAT\File\FileFinder;
use PhpAT\Parser\NamespaceExtractor;
use PhpAT\Parser\ParentExtractor;
use PhpParser\NodeTraverserInterface;
use PhpParser\Parser;

class Inheritance implements RuleType
{
    private $traverser;
    private $finder;
    private $parser;

    public function __construct(FileFinder $finder, Parser $parser, NodeTraverserInterface $traverser)
    {
        $this->finder = $finder;
        $this->parser = $parser;
        $this->traverser = $traverser;
    }

    public function validate(array $parsedClass, array $params): bool
    {
        $parentExtractor = new ParentExtractor();
        $this->traverser->addVisitor($parentExtractor);
        $this->traverser->traverse($parsedClass);
        $this->traverser->removeVisitor($parentExtractor);

        $filesFound = $this->finder->findFiles($params['file']);
        $namespaceExtractor = new NamespaceExtractor();
        $this->traverser->addVisitor($namespaceExtractor);
        /** @var \SplFileInfo $file */
        foreach ($filesFound as $file) {
            $parsedFile = $this->parser->parse(file_get_contents($file->getPathname()));
            $this->traverser->traverse($parsedFile);
        }
        $this->traverser->removeVisitor($namespaceExtractor);

        $result = $parentExtractor->getResult();
        if (empty($result)) {
            return false;
        }

        if (in_array(reset($result), $namespaceExtractor->getResult())) {
            return true;
        }

        return false;
    }

    public function getMessageVerb(): string
    {
        return 'extend';
    }
}
