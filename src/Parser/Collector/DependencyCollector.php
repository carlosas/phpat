<?php

namespace PhpAT\Parser\Collector;

use PhpAT\Parser\ClassMatcher;
use PhpAT\Parser\ClassName;
use PhpParser\Node;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\PhpDocParser\Parser\TokenIterator;

class DependencyCollector extends AbstractCollector
{
    /**
     * @var ClassMatcher
     */
    private $matcher;
    /**
     * @var bool
     */
    private $ignoreDocBlocks;
    /**
     * @var array
     */
    private $dependencies = [];
    /**
     * @var PhpDocParser
     */
    private $docParser;

    public function __construct(PhpDocParser $docParser, ClassMatcher $matcher, bool $ignoreDocBlocks)
    {
        $this->docParser = $docParser;
        $this->matcher = $matcher;
        $this->ignoreDocBlocks = $ignoreDocBlocks;
    }

    public function leaveNode(Node $node)
    {
        if ($node instanceof Node\Stmt\UseUse) {
            $this->matcher->addDeclaration($node->name, $node->alias);
        } elseif ($node instanceof Node\Name\FullyQualified) {
            if ($node->toString() == '\var_dump') {
                echo '--------------1-------------------------------------------------------';
                die;
            }
            $this->saveResultIfNotPresent($node->toString());
        } elseif ($node instanceof Node\Name) {
            $found = $this->matcher->findClass($node->parts);
            if ($found == '\var_dump') {
                var_dump($node);
                die;
            }
            if ($found !== null) {
                $this->saveResultIfNotPresent($found);
            }
//        } elseif (!$this->ignoreDocBlocks && $node->getDocComment() !== null) {
//            $doc = $node->getDocComment()->getText();
//            $nodes = $this->docParser->parse(new TokenIterator((new Lexer())->tokenize($doc)));
//            foreach ($nodes->getTags() as $tag) {
//                if (isset($tag->value->type->name)) {
//                    $type = $tag->value->type->name;
//                    $class = $this->matcher->findClass(explode('\\', $type)) ?? $type;
//                    $this->saveResultIfNotPresent($class);
//                }
//            }
        }
    }

    private function saveResultIfNotPresent(string $fqcn)
    {
        if (array_search($fqcn, $this->dependencies) === false) {
            $this->dependencies[] = $fqcn;
            $this->result[] = ClassName::createFromFQCN($fqcn);
        }
    }
}
