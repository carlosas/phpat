<?php

namespace PhpAT\Parser\Ast\Extractor;

use PhpAT\App\Configuration;
use PhpAT\Parser\Ast\Collector\MethodDependenciesCollector;
use PhpAT\Parser\Ast\FullClassName;
use PhpAT\Parser\Ast\NodeTraverser;
use PhpAT\Parser\Ast\Type\PhpDocTypeResolver;
use PhpAT\Parser\Ast\Type\PhpType;
use PhpAT\Parser\Relation\AbstractRelation;
use PhpAT\Parser\Relation\Dependency;
use phpDocumentor\Reflection\Types\Context;
use PhpParser\Comment;
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

        $this->addClassDependencies($class, $context);

        /** @var ReflectionProperty $property */
        foreach ($class->getImmediateProperties() as $property) {
            $this->addPropertyDependencies($property, $context);
        }

        /** @var ReflectionMethod $method */
        foreach ($class->getImmediateMethods() as $method) {
            $this->addMethodDependencies($method, $context);
        }

        return $this->flushRelations();
    }

    private function addPropertyDependencies(ReflectionProperty $property, Context $context): void
    {
        $type = $property->getType();
        if (
            $type !== null
            && !PhpType::isBuiltinType($type->getName())
            && !PhpType::isSpecialType($type->getName())
        ) {
            $this->addRelation(
                Dependency::class,
                $property->getStartLine(),
                FullClassName::createFromFQCN($type->getName())
            );
        }

        foreach ($this->docTypeResolver->getBlockClassNames($context, $property->getDocComment()) as $type) {
            if (
                $type !== null
                && !PhpType::isBuiltinType($type)
                && !PhpType::isSpecialType($type)
            ) {
                $this->addRelation(
                    Dependency::class,
                    $property->getStartLine(),
                    FullClassName::createFromFQCN($type)
                );
            }
        }
    }

    /**
     * @param ReflectionClass $class
     * @param Context $context
     */
    private function addClassDependencies(ReflectionClass $class, Context $context): void
    {
        // Class doc comment
        $doc = $class->getDocComment();
        foreach ($this->docTypeResolver->getBlockClassNames($context, $doc) as $type) {
            if (
                $type !== null
                && !PhpType::isBuiltinType($type)
                && !PhpType::isSpecialType($type)
            ) {
                $this->addRelation(
                    Dependency::class,
                    $class->getStartLine(),
                    FullClassName::createFromFQCN($type)
                );
            }
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
        $type = $method->getReturnType();
        if (
            $type !== null
            && !PhpType::isBuiltinType($type->getName())
            && !PhpType::isSpecialType($type->getName())
        ) {
            $this->addRelation(
                Dependency::class,
                $method->getStartLine(),
                FullClassName::createFromFQCN($type->getName())
            );
        }

        // Method parameters
        /** @var ReflectionParameter $parameter */
        foreach ($method->getParameters() as $parameter) {
            $type = $parameter->getType();
            if (
                $type !== null
                && !PhpType::isBuiltinType($type->getName())
                && !PhpType::isSpecialType($type->getName())
            ) {
                $this->addRelation(
                    Dependency::class,
                    $method->getStartLine(),
                    FullClassName::createFromFQCN($parameter->getType()->getName())
                );
            }
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
            $fqcn = $result->relatedClass->getFQCN();
            if (
                !PhpType::isBuiltinType($fqcn)
                && !PhpType::isSpecialType($fqcn)
            ) {
                $this->addRelation(
                    Dependency::class,
                    $result->line,
                    $result->relatedClass
                );
            }
        }
    }
}
