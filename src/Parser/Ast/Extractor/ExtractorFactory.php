<?php

namespace PhpAT\Parser\Ast\Extractor;

use PhpAT\App\Configuration;
use PhpAT\Parser\Ast\PhpDocTypeResolver;

class ExtractorFactory
{
    /** @var PhpDocTypeResolver */
    private $docTypeResolver;
    /** @var Configuration */
    private $configuration;

    public function __construct(
        PhpDocTypeResolver $docTypeResolver,
        Configuration $configuration
    ) {
        $this->docTypeResolver = $docTypeResolver;
        $this->configuration = $configuration;
    }

    public function createDependencyExtractor(): DependencyExtractor
    {
        return new DependencyExtractor($this->docTypeResolver, $this->configuration);
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
