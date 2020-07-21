<?php

namespace PhpAT\Parser\Ast\Extractor;

use PhpAT\App\Configuration;
use PhpAT\Parser\Ast\Collector\NewDependencyCollector;
use PhpAT\Parser\Ast\FullClassName;
use PhpAT\Parser\Ast\NodeTraverser;
use PhpAT\Parser\Ast\PhpDocTypeResolver;
use PhpAT\Parser\Relation\AbstractRelation;
use PhpAT\Parser\Relation\Dependency;
use phpDocumentor\Reflection\Types\Context;
use PhpParser\Node;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\PhpDocParser\Parser\TokenIterator;
use Roave\BetterReflection\Reflection\ReflectionClass;
use Roave\BetterReflection\Reflection\ReflectionMethod;
use Roave\BetterReflection\Reflection\ReflectionParameter;
use Roave\BetterReflection\TypesFinder\PhpDocumentor\NamespaceNodeToReflectionTypeContext;

class DependencyExtractor extends AbstractExtractor
{
    /** @var PhpDocParser */
    private $docParser;
    /** @var PhpDocTypeResolver */
    private $docTypeResolver;
    /** @var Configuration */
    private $configuration;
    /** @var NodeTraverser */
    private $traverser;
    /** @var string[] */
    private $found = [];

    public function __construct(
        PhpDocParser $docParser,
        PhpDocTypeResolver $docTypeResolver,
        Configuration $configuration
    ) {
        $this->docParser = $docParser;
        $this->docTypeResolver = $docTypeResolver;
        $this->configuration = $configuration;
        $this->traverser = new NodeTraverser();
    }

    /**
     * @param ReflectionClass $class
     * @return AbstractRelation[]
     */
    public function extract(ReflectionClass $class): array
    {
        $context = (new NamespaceNodeToReflectionTypeContext())($class->getDeclaringNamespaceAst());

        try {
            /** @var ReflectionMethod $method */
            foreach ($class->getImmediateMethods() as $method) {
                $this->addMethodDependencies($method, $context);
            }
        } catch (\Throwable $e) {
            echo $e->getMessage(); die;
        }

        return $this->flushRelations();
    }

    /**
     * @param ReflectionMethod $method
     * @param Context $context
     * @return void
     */
    private function addMethodDependencies(ReflectionMethod $method, Context $context): void
    {
        // Method return
        $returnType = $method->getReturnType();
        if ($this->isClassType($returnType) && !$returnType->isBuiltin()) {
            $this->addRelation(
                Dependency::class,
                $method->getStartLine(),
                FullClassName::createFromFQCN($returnType->getName())
            );
        }
        // Method parameters
        /** @var ReflectionParameter $parameter */
        foreach ($method->getParameters() as $parameter) {
            if ($this->isClassType($parameter->getType()) && !$parameter->getType()->isBuiltin()) {
                $this->addRelation(
                    Dependency::class,
                    $method->getStartLine(),
                    FullClassName::createFromFQCN($parameter->getType()->getName())
                );
            }
        }

        //TODO: DocBlock classes
        // DocBlock
        //$ast = $method->getDocComment();
        //var_dump($ast);
        //die;

        // Method body
        $extractor = new NewDependencyCollector(
            $this->configuration,
            $this->docParser,
            $this->docTypeResolver,
            $context
        );
        $this->traverser->addVisitor($extractor);
        $this->traverser->traverse([$method->getAst()]);

//        /** @var Node\Name $name */
//        foreach ($names as $name) {
//            if (!PhpType::isBuiltinType($name->toString())) {
//                var_dump(($context->getResolvedClassName($name))->toString());
//            }
//        }
    }

    private function extractDocClassNames(string $docBlock): array
    {
        $nodes = $this->docParser->parse(new TokenIterator((new Lexer())->tokenize($docBlock)));
        foreach ($nodes->getTags() as $tag) {
            if (isset($tag->value->type)) {
                $names = $this->docTypeResolver->resolve($tag->value->type);
                foreach ($names as $name) {
                    $nameNode = strpos($name, '\\') === 0
                        ? new Node\Name\FullyQualified($name)
                        : new Node\Name($name);
                    $result[] = $this->nameContext->getResolvedClassName($nameNode);
                }
            }
        }

        return $result ?? [];
    }
}
