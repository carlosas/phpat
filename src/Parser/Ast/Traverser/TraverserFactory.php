<?php

namespace PhpAT\Parser\Ast\Traverser;

use PhpAT\App\Configuration;
use PhpAT\Parser\Ast\Collector\ClassCollector;
use PhpAT\Parser\Ast\Collector\InterfaceCollector;
use PhpAT\Parser\Ast\Collector\MethodDependenciesCollector;
use PhpAT\Parser\Ast\Collector\ParentCollector;
use PhpAT\Parser\Ast\Collector\TraitCollector;
use PhpAT\Parser\Ast\Type\PhpStanDocTypeNodeResolver;
use PhpParser\NodeTraverser;

class TraverserFactory
{
    /** @var Configuration */
    private $configuration;
    /** @var PhpStanDocTypeNodeResolver */
    private $docTypeResolver;

    public function __construct(
        Configuration $configuration,
        PhpStanDocTypeNodeResolver $docTypeResolver
    ) {
        $this->configuration = $configuration;
        $this->docTypeResolver = $docTypeResolver;
    }

    public function create(): NodeTraverser
    {
        $traverser = new NodeTraverser();

        $nameResolver = new NameResolver();
        $traverser->addVisitor($nameResolver);
        $classCollector = new ClassCollector();
        $traverser->addVisitor($classCollector);
        $interfaceCollector = new InterfaceCollector();
        $traverser->addVisitor($interfaceCollector);
        $traitCollector = new TraitCollector();
        $traverser->addVisitor($traitCollector);
        $parentCollector = new ParentCollector();
        $traverser->addVisitor($parentCollector);
        $dependencyCollector = new MethodDependenciesCollector(
            $this->configuration,
            $this->docTypeResolver,
            $nameResolver->getNameContext()
        );
        $traverser->addVisitor($dependencyCollector);

        return $traverser;
    }
}
