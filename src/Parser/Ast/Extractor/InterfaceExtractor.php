<?php

namespace PhpAT\Parser\Ast\Extractor;

use PhpAT\App\Configuration;
use PhpAT\Parser\Ast\FullClassName;
use PhpAT\Parser\Relation\AbstractRelation;
use PhpAT\Parser\Relation\Composition;
use Roave\BetterReflection\Reflection\ReflectionClass;

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
        try {
            /** @var ReflectionClass $interface */
            foreach ($class->getInterfaces() as $interface) {
                $this->addRelation(
                    Composition::class,
                    $class->getStartLine(),
                    FullClassName::createFromFQCN($interface->getName())
                );
            }
        } catch (\Throwable $e) {
            //TODO: Maybe change reflection source to Composer autoload
        }

        return $this->flushRelations();
    }
}
