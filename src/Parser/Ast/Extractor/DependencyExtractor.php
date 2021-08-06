<?php

namespace PhpAT\Parser\Ast\Extractor;

use PhpAT\App\Configuration;
use PhpAT\Parser\Ast\Collector\MethodDependenciesCollector;
use PhpAT\Parser\Ast\FullClassName;
use PhpAT\Parser\Ast\NodeTraverser;
use PhpAT\Parser\Ast\Type\NamespaceNodeToReflectionContext;
use PhpAT\Parser\Ast\Type\PhpParserTypeNodeResolver;
use PhpAT\Parser\Ast\Type\PhpStanDocTypeNodeResolver;
use PhpAT\Parser\Ast\Type\PhpType;
use PhpAT\Parser\Relation\AbstractRelation;
use PhpAT\Parser\Relation\Dependency;
use phpDocumentor\Reflection\Types\Context;
use PhpParser\Node;
use PHPStan\BetterReflection\Reflection\ReflectionClass;
use PHPStan\BetterReflection\Reflection\ReflectionMethod;
use PHPStan\BetterReflection\Reflection\ReflectionProperty;

class DependencyExtractor extends AbstractExtractor
{
    /** @var PhpStanDocTypeNodeResolver */
    private $docTypeResolver;
    /** @var Configuration */
    private $configuration;
    /** @var NodeTraverser */
    private $traverser;
    /** @var ExtractorFactory */
    private $extractorFactory;
    /** @var PhpParserTypeNodeResolver */
    private $typeNodeResolver;

    public function __construct(
        PhpStanDocTypeNodeResolver $docTypeResolver,
        Configuration $configuration,
        ExtractorFactory $extractorFactory
    ) {
        $this->docTypeResolver = $docTypeResolver;
        $this->configuration = $configuration;
        $this->traverser = new NodeTraverser();
        $this->extractorFactory = $extractorFactory;
        $this->typeNodeResolver = new PhpParserTypeNodeResolver();
    }

    /**
     * @param ReflectionClass $class
     * @return AbstractRelation[]
     */
    public function extract(ReflectionClass $class): array
    {
        $context = (new NamespaceNodeToReflectionContext())($class->getDeclaringNamespaceAst());

        $this->addClassDependencies($class, $context);

        foreach ($class->getImmediateProperties() as $property) {
            $this->addPropertyDependencies($property, $context);
        }

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
        $ast = $property->getAst();
        $propertyTypes = $this->typeNodeResolver->getTypeClassNames($ast->type);

        foreach ($propertyTypes as $propertyType) {
            if (!PhpType::isBuiltinType($propertyType) && !PhpType::isSpecialType($propertyType)) {
                $this->addRelation(
                    Dependency::class,
                    $property->getStartLine(),
                    FullClassName::createFromFQCN($propertyType)
                );
            }
        }

        $this->addDocCommentDependencies($property->getDocComment(), $property->getStartLine(), $context);
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

        $this->addDocCommentDependencies($class->getDocComment(), $class->getStartLine(), $context);
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
            $returnTypes = $this->typeNodeResolver->getTypeClassNames($ast->returnType);

            foreach ($returnTypes as $returnType) {
                if (!PhpType::isBuiltinType($returnType) && !PhpType::isSpecialType($returnType)) {
                    $this->addRelation(
                        Dependency::class,
                        $method->getStartLine(),
                        FullClassName::createFromFQCN($returnType)
                    );
                }
            }
        }

        // Method parameters
        foreach ($method->getParameters() as $parameter) {
            $ast = $parameter->getAst();
            if ($ast->type !== null) {
                $paramTypes = $this->typeNodeResolver->getTypeClassNames($ast->type);

                foreach ($paramTypes as $paramType) {
                    if (!PhpType::isBuiltinType($paramType) && !PhpType::isSpecialType($paramType)) {
                        $this->addRelation(
                            Dependency::class,
                            $method->getStartLine(),
                            FullClassName::createFromFQCN($paramType)
                        );
                    }
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

    private function addDocCommentDependencies(string $docComment, int $startLine, Context $context)
    {
        foreach ($this->docTypeResolver->getBlockClassNames($context, $docComment) as $type) {
            if ($type !== null && !PhpType::isBuiltinType($type) && !PhpType::isSpecialType($type)) {
                $this->addRelation(
                    Dependency::class,
                    $startLine,
                    FullClassName::createFromFQCN($type)
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
}
