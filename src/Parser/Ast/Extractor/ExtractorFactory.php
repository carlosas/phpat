<?php

namespace PhpAT\Parser\Ast\Extractor;

use PhpAT\App\Configuration;
use PhpAT\Parser\Ast\Type\PhpStanDocTypeNodeResolver;

class ExtractorFactory
{
    /** @var PhpStanDocTypeNodeResolver */
    private $docTypeResolver;
    /** @var Configuration */
    private $configuration;

    public function __construct(
        PhpStanDocTypeNodeResolver $docTypeResolver,
        Configuration $configuration
    ) {
        $this->docTypeResolver = $docTypeResolver;
        $this->configuration = $configuration;
    }

    public function createDependencyExtractor(): DependencyExtractor
    {
        return new DependencyExtractor($this->docTypeResolver, $this->configuration, $this);
    }

    public function createParentExtractor(): ParentExtractor
    {
        return new ParentExtractor($this->configuration);
    }

    public function createInterfaceExtractor(): InterfaceExtractor
    {
        return new InterfaceExtractor($this->configuration);
    }

    public function createTraitExtractor(): TraitExtractor
    {
        return new TraitExtractor($this->configuration);
    }
}
