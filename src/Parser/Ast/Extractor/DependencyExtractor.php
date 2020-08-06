<?php

namespace PhpAT\Parser\Ast\Extractor;

use PhpAT\App\Configuration;
use PhpAT\Parser\Ast\Collector\MethodDependenciesCollector;
use PhpAT\Parser\Ast\FullClassName;
use PhpAT\Parser\Ast\NodeTraverser;
use PhpAT\Parser\Ast\PhpDocTypeResolver;
use PhpAT\Parser\Relation\AbstractRelation;
use PhpAT\Parser\Relation\Dependency;
use phpDocumentor\Reflection\Types\Context;
use Roave\BetterReflection\Reflection\ReflectionClass;
use Roave\BetterReflection\Reflection\ReflectionMethod;
use Roave\BetterReflection\Reflection\ReflectionParameter;
use Roave\BetterReflection\Reflection\ReflectionProperty;
use Roave\BetterReflection\TypesFinder\PhpDocumentor\NamespaceNodeToReflectionTypeContext;

class DependencyExtractor extends AbstractExtractor
{
    /** @var PhpDocTypeResolver */
    private $docTypeResolver;
    /** @var Configuration */
    private $configuration;
    /** @var NodeTraverser */
    private $traverser;
    /** @var string[] */
    private $found = [];

    public function __construct(
        PhpDocTypeResolver $docTypeResolver,
        Configuration $configuration
    ) {
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
            /** @var ReflectionProperty $property */
            foreach ($class->getImmediateProperties() as $property) {
                $this->addPropertyDependencies($property, $context);
            }

            /** @var ReflectionMethod $method */
            foreach ($class->getImmediateMethods() as $method) {
                $this->addMethodDependencies($method, $context);
            }
        } catch (\Throwable $e) {
            echo $e->getMessage(); die;
        }

        return $this->flushRelations();
    }

    private function addPropertyDependencies(ReflectionProperty $property, Context $context): void
    {
        if (!is_null($property->getType())) {
            $this->addRelation(
                Dependency::class,
                $property->getStartLine(),
                FullClassName::createFromFQCN($property->getType()->getName())
            );
        }

        foreach ($this->docTypeResolver->getBlockClassNames($context, $property->getDocComment()) as $docType) {
            $this->addRelation(
                Dependency::class,
                $property->getStartLine(),
                FullClassName::createFromFQCN($docType)
            );
        }
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
        // Docblocks
        foreach ($this->docTypeResolver->getBlockClassNames($context, $method->getDocComment()) as $docType) {
            $this->addRelation(
                Dependency::class,
                $method->getStartLine(),
                FullClassName::createFromFQCN($docType)
            );
        }
        // Method body
        $collector = new MethodDependenciesCollector(
            $this->configuration,
            $this->docTypeResolver,
            $context
        );
        $this->traverser->addVisitor($collector);
        $this->traverser->traverse([$method->getAst()]);

        /** @var Dependency $result */
        foreach ($collector->getResults() as $result) {
            $this->addRelation(
                Dependency::class,
                $result->line,
                $result->relatedClass
            );
        }
    }
}
