<?php

namespace PhpAT\Parser\Ast\Classmap;

use PhpAT\Parser\Ast\FullClassName;

final class ClassmapItem
{
    /** @var string */
    private $pathname;
    /** @var string */
    private $classType;
    /** @var int|null */
    private $flag;
    /** @var array */
    private $classesDepended = [];
    /** @var FullClassName|null */
    private $classExtended = null;
    /** @var FullClassName[] */
    private $interfacesImplemented = [];
    /** @var FullClassName[] */
    private $includedTraits = [];

    public function __construct(string $pathname, string $classType, ?int $flag)
    {
        $this->pathname = $pathname;
        $this->classType = $classType;
        $this->flag = $flag;
    }

    public function addDependency(FullClassName $className): void
    {
        $this->classesDepended[] = $className;
    }

    public function addParent(FullClassName $className): void
    {
        $this->classExtended = $className;
    }

    public function addInterface(FullClassName $className): void
    {
        $this->interfacesImplemented[] = $className;
    }

    public function addTrait(FullClassName $className): void
    {
        $this->includedTraits[] = $className;
    }

    public function getClassType(): string
    {
        return $this->classType;
    }

    public function getPathname(): string
    {
        return $this->pathname;
    }

    public function getFlag(): ?int
    {
        return $this->flag;
    }

    /** @return  FullClassName[] */
    public function getDependencies(): array
    {
        return $this->classesDepended;
    }

    /** @return  FullClassName|null */
    public function getParent(): ?FullClassName
    {
        return $this->classExtended;
    }

    /** @return  FullClassName[] */
    public function getInterfaces(): array
    {
        return $this->interfacesImplemented;
    }

    /** @return  FullClassName[] */
    public function getTraits(): array
    {
        return $this->includedTraits;
    }
}
