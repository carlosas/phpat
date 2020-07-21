<?php

namespace PhpAT\Parser\Ast\Collector;

use PhpAT\App\Configuration;
use PhpAT\Parser\Ast\FullClassName;
use PhpAT\Parser\Ast\PhpDocTypeResolver;
use PhpAT\Parser\Ast\PhpType;
use PhpAT\Parser\Relation\Dependency;
use phpDocumentor\Reflection\Types\Context;
use PhpParser\Comment\Doc;
use PhpParser\Node;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\PhpDocParser\Parser\TokenIterator;

class NewDependencyCollector extends AbstractRelationCollector
{
    /**
     * @var string[]
     */
    private $found = [];
    /**
     * @var Configuration
     */
    private $configuration;
    /**
     * @var PhpDocParser
     */
    private $docParser;
    /**
     * @var PhpDocTypeResolver
     */
    private $docTypeResolver;
    /**
     * @var string[]
     */
    private $aliases;
    /**
     * @var NodeCollectorHelper
     */
    private $helper;

    public function __construct(
        Configuration $configuration,
        PhpDocParser $docParser,
        PhpDocTypeResolver $docTypeResolver,
        Context $context
    ) {
        $this->configuration = $configuration;
        $this->docParser = $docParser;
        $this->docTypeResolver = $docTypeResolver;
        $this->aliases = $context->getNamespaceAliases();
        $this->helper = new NodeCollectorHelper();
    }

    public function beforeTraverse(array $nodes)
    {
        parent::beforeTraverse($nodes);
        $this->found = [];

        return $nodes;
    }

    public function leaveNode(Node $node)
    {
      //  $this->helper->recordExtendsUsage($node);
      //  $this->helper->recordImplementsUsage($node);
//        $this->helper->recordClassExpressionUsage($node);
//        $this->helper->recordCatchUsage($node);
        //$this->helper->recordFunctionCallUsage($node);
      //  $this->helper->recordFunctionParameterTypesUsage($node);
      //  $this->helper->recordFunctionReturnTypeUsage($node);
        //$this->helper->recordConstantFetchUsage($node);
      //  $this->helper->recordTraitUsage($node);

        foreach ($this->helper->getCollectedSymbols() as $symbol) {
            if (
                !PhpType::isBuiltinType($symbol)
                && !PhpType::isSpecialType($symbol)
            ) {
                if (is_string($symbol)) {
                    $this->addDependency($node->getLine(), $symbol);
                }
                //var_dump($symbol);
            }
        }
/*
        if ($node instanceof Node\Name) {
            $resolvedName = $this->resolveName($node);
            if (is_string($resolvedName)) {
                $this->addDependency($node->getLine(), $resolvedName);
            }
        }

        if (!$this->configuration->getIgnoreDocBlocks() && $node->getDocComment() !== null) {
            foreach ($this->extractDocClassNames($node->getDocComment()) as $class) {
                $this->addDependency($node->getLine(), $class);
            }
        }
*/
        return $node;
    }

    private function resolveName(Node\Name $name): ?string
    {
        if ($name->isFullyQualified()) {
            return $name->toString();
        }

        if ($name->isSpecialClassName()) {
            return null;
        }

        var_dump($name->getType());
/*echo '------------------' . PHP_EOL;
var_dump($name->parts);
echo $this->getFQCNFromParts($name->parts) . PHP_EOL;
echo '------------------' . PHP_EOL;*/
        $parts = $name->getSubNodeNames();
        $link = array_shift($parts);

        if (isset($this->aliases[$link])) {
            return empty($parts) ? $this->aliases[$link] : $this->aliases[$link] . '\\' . implode('\\', $parts);
        }

        return implode('\\', $parts);
    }

    private function addDependency(int $line, string $fqcn): void
    {
        $class = FullClassName::createFromFQCN($fqcn);
        if (!array_key_exists($fqcn, $this->found) && $this->isAutoloaded($fqcn)) {
            $this->found[$fqcn] = $class->getFQCN();
            $this->results[] = new Dependency($line, $class);
        }
    }

    private function isAutoloaded(string $fqcn): bool
    {
        return class_exists($fqcn) || interface_exists($fqcn) || trait_exists($fqcn);
    }

    private function extractDocClassNames(Doc $doc): array
    {
        $nodes = $this->docParser->parse(new TokenIterator((new Lexer())->tokenize($doc->getText())));
        foreach ($nodes->getTags() as $tag) {
            if (isset($tag->value->type)) {
                $names = $this->docTypeResolver->resolve($tag->value->type);

                foreach ($names as $name) {
                    $nameNode = strpos($name, '\\') === 0
                        ? new Node\Name\FullyQualified($name)
                        : new Node\Name($name);
                    $resolvedName = $this->resolveName($nameNode);
                    if (is_string($resolvedName)) {
                        $result[] = $resolvedName;
                    }
                }
            }
        }

        return $result ?? [];
    }
}
