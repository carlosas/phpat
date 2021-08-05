<?php

namespace PhpAT\Parser\Ast\Extractor;

use PhpAT\App\Configuration;
use PhpAT\Parser\Ast\FullClassName;
use PhpAT\Parser\Relation\AbstractRelation;
use PhpAT\Parser\Relation\Composition;
use PhpParser\Node\Name\FullyQualified;
use PHPStan\BetterReflection\Reflection\ReflectionClass;

class InterfaceExtractor extends AbstractExtractor
{
    /** @var Configuration */
    private $configuration;

    public function __construct(
        Configuration $configuration
    ) {
        $this->configuration = $configuration;
    }

    /**
     * @param ReflectionClass $class
     * @return AbstractRelation[]
     */
    public function extract(ReflectionClass $class): array
    {
        $ast = $class->getAst();

        if (!isset($ast->implements)) {
            return $this->flushRelations();
        }

        foreach ($ast->implements as $interface) {
            if ($interface instanceof FullyQualified) {
                $this->addRelation(
                    Composition::class,
                    $class->getStartLine(),
                    FullClassName::createFromFQCN($interface->toString())
                );
            }
        }

        return $this->flushRelations();
    }
}
