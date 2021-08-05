<?php

namespace PhpAT\Parser\Ast\Extractor;

use PhpAT\App\Configuration;
use PhpAT\Parser\Ast\FullClassName;
use PhpAT\Parser\Relation\AbstractRelation;
use PhpAT\Parser\Relation\Mixin;
use PhpParser\Node;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\TraitUse;
use PHPStan\BetterReflection\Reflection\ReflectionClass;

class TraitExtractor extends AbstractExtractor
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

        if (!isset($ast->stmts)) {
            return $this->flushRelations();
        }

        $traits = array_merge(
            [],
            ...array_map(
                static function (TraitUse $traitUse): array {
                    return $traitUse->traits;
                },
                array_filter(
                    $ast->stmts,
                    static function (Node $node): bool {
                        return $node instanceof TraitUse;
                    }
                )
            )
        );

        foreach ($traits as $trait) {
            if ($trait instanceof FullyQualified) {
                $this->addRelation(
                    Mixin::class,
                    $trait->getStartLine(),
                    FullClassName::createFromFQCN($trait->toString())
                );
            }
        }

        return $this->flushRelations();
    }
}
