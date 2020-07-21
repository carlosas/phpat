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
            foreach ($class->getTraitNames() as $trait) {
                $this->addRelation(Mixin::class, $class->getStartLine(), FullClassName::createFromFQCN($trait));
            }
        } catch (\Throwable $e) {
            //TODO: Change reflection source to Composer autoload
        }

        return $result ?? [];
    }
}
