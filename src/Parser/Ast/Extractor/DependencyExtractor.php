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
use PhpParser\Node;
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
    /** @var ExtractorFactory */
    private $extractorFactory;

    public function __construct(
        PhpDocTypeResolver $docTypeResolver,
        Configuration $configuration,
        ExtractorFactory $extractorFactory
    ) {
        $this->docTypeResolver = $docTypeResolver;
        $this->configuration = $configuration;
        $this->traverser = new NodeTraverser();
        $this->extractorFactory = $extractorFactory;
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

    /**
     * @param ReflectionProperty $property
     * @param Context $context
     */
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
        $this->addDependenciesFromRelations(
            $this->extractorFactory->createParentExtractor()->extract($class)
        );

        $this->addDependenciesFromRelations(
            $this->extractorFactory->createInterfaceExtractor()->extract($class)
        );

        $this->addDependenciesFromRelations(
            $this->extractorFactory->createTraitExtractor()->extract($class)
        );

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
     */
    private function addMethodDependencies(ReflectionMethod $method, Context $context): void
    {
        // Method return
        $ast = $method->getAst();
        if (
            ($ast instanceof Node\Stmt\ClassMethod || $ast instanceof Node\Stmt\Function_)
            && $ast->returnType !== null
        ) {
            $returnType = $this->getNodeType($ast->returnType);

            if (
                $returnType !== null
                && !PhpType::isBuiltinType($returnType)
                && !PhpType::isSpecialType($returnType)
            ) {
                $this->addRelation(
                    Dependency::class,
                    $method->getStartLine(),
                    FullClassName::createFromFQCN($returnType)
                );
            }
        }

        // Method parameters
        /** @var ReflectionParameter $parameter */
        foreach ($method->getParameters() as $parameter) {
            $ast = $parameter->getAst();
            if (property_exists($ast, 'type') && $ast->type !== null) {
                $paramType = $this->getNodeType($ast->type);

                if (!PhpType::isBuiltinType($paramType) && !PhpType::isSpecialType($paramType)) {
                    $this->addRelation(
                        Dependency::class,
                        $method->getStartLine(),
                        FullClassName::createFromFQCN($paramType)
                    );
                }
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

    /**
     * @param AbstractRelation[] $relations
     */
    private function addDependenciesFromRelations(array $relations): void
    {
        foreach ($relations as $relation) {
            $this->addRelation(
                Dependency::class,
                $relation->line,
                $relation->relatedClass
            );
        }
    }

    private function getNodeType(Node $node): ?string
    {
        switch (true) {
            case $node instanceof Node\Identifier:
            case $node instanceof Node\Name\FullyQualified:
            case $node instanceof Node\Name:
                return $node->toString();
            case $node instanceof Node\NullableType:
                return $node->type->toString();
        }

        return null;
    }
}
