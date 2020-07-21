<?php

namespace PhpAT\Parser\Ast\Extractor;

use PhpAT\App\Configuration;
use PhpAT\Parser\Ast\PhpDocTypeResolver;
use PHPStan\PhpDocParser\Parser\PhpDocParser;

class ExtractorFactory
{
    /** @var PhpDocParser */
    private $docParser;
    /** @var PhpDocTypeResolver */
    private $docTypeResolver;
    /** @var Configuration */
    private $configuration;

    public function __construct(
        PhpDocParser $docParser,
        PhpDocTypeResolver $docTypeResolver,
        Configuration $configuration
    ) {
        $this->docParser = $docParser;
        $this->docTypeResolver = $docTypeResolver;
        $this->configuration = $configuration;
    }

    public function createDependencyExtractor(): DependencyExtractor
    {
        return new DependencyExtractor($this->docParser, $this->docTypeResolver, $this->configuration);
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
