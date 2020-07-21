<?php

namespace PhpAT\Parser\Ast\Extractor;

use PhpAT\App\Configuration;
use PhpAT\Parser\Ast\FullClassName;
use PhpAT\Parser\Relation\AbstractRelation;
use PhpAT\Parser\Relation\Inheritance;
use Roave\BetterReflection\Reflection\ReflectionClass;

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
        try {
            foreach ($class->getParentClassNames() as $p) {
                $this->addRelation(Inheritance::class, $class->getStartLine(), FullClassName::createFromFQCN($p));
            }
        } catch (\Throwable $e) {
            //var_dump($e->getMessage());
            //TODO: Change reflection source to Composer autoload
        }

        return $this->flushRelations();
    }
}
