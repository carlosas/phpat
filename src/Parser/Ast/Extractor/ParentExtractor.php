<?php

namespace PhpAT\Parser\Ast\Extractor;

use PhpAT\App\Configuration;
use PhpAT\Parser\Ast\FullClassName;
use PhpAT\Parser\Relation\AbstractRelation;
use PhpAT\Parser\Relation\Inheritance;
use PhpParser\Node\Name\FullyQualified;
use PHPStan\BetterReflection\Reflection\ReflectionClass;

class ParentExtractor extends AbstractExtractor
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

        if (!isset($ast->extends)) {
            return $this->flushRelations();
        }

        if ($class->isInterface() || $class->isTrait()) {
            /** @var FullyQualified $parent */
            foreach ($ast->extends as $parent) {
                $this->addRelation(
                    Inheritance::class,
                    $class->getStartLine(),
                    FullClassName::createFromFQCN($parent->toString())
                );
            }
        } elseif ($ast->extends instanceof FullyQualified) {
            $this->addRelation(
                Inheritance::class,
                $class->getStartLine(),
                FullClassName::createFromFQCN($ast->extends->toString())
            );
        }

        return $this->flushRelations();
    }
}
