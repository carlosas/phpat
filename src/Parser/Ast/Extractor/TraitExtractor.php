<?php

namespace PhpAT\Parser\Ast\Extractor;

use PhpAT\App\Configuration;
use PhpAT\Parser\Ast\FullClassName;
use PhpAT\Parser\Relation\AbstractRelation;
use PhpAT\Parser\Relation\Mixin;
use Roave\BetterReflection\Reflection\ReflectionClass;

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
        try {
            /** @var ReflectionClass $trait */
            foreach ($class->getTraits() as $trait) {
                $this->addRelation(
                    Mixin::class,
                    $trait->getStartLine(),
                    FullClassName::createFromFQCN($trait->getName())
                );
            }
        } catch (\Throwable $e) {
            //TODO: Maybe change reflection source to Composer autoload
        }

        return $this->flushRelations();
    }
}
