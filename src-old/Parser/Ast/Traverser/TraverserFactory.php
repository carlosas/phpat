<?php

namespace PHPatOld\Parser\Ast\Traverser;

use PHPatOld\App\Configuration;
use PHPatOld\Parser\Ast\Collector\ClassCollector;
use PHPatOld\Parser\Ast\Collector\ClassDependenciesCollector;
use PHPatOld\Parser\Ast\Collector\InterfaceCollector;
use PHPatOld\Parser\Ast\Collector\ParentCollector;
use PHPatOld\Parser\Ast\Collector\TraitCollector;
use PHPatOld\Parser\Ast\Type\PhpStanDocTypeNodeResolver;
use PhpParser\NodeTraverser;

class TraverserFactory
{
    private Configuration $configuration;
    private PhpStanDocTypeNodeResolver $docTypeResolver;

    public function __construct(
        Configuration $configuration,
        PhpStanDocTypeNodeResolver $docTypeResolver
    ) {
        $this->configuration   = $configuration;
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
        $dependencyCollector = new ClassDependenciesCollector(
            $this->configuration,
            $this->docTypeResolver,
            $nameResolver->getNameContext()
        );
        $traverser->addVisitor($dependencyCollector);

        return $traverser;
    }
}
