<?php

namespace PhpAT\Parser\Ast\Classmap;

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
    /** @var ClassmapRelation|null */
    private $classExtended = null;
    /** @var ClassmapRelation[] */
    private $interfacesImplemented = [];
    /** @var ClassmapRelation[] */
    private $includedTraits = [];

    public function __construct(string $pathname, string $classType, ?int $flag)
    {
        $this->pathname = $pathname;
        $this->classType = $classType;
        $this->flag = $flag;
    }

    public function addDependency(ClassmapRelation $className): void
    {
        $this->classesDepended[] = $className;
    }

    public function addParent(ClassmapRelation $className): void
    {
        $this->classExtended = $className;
    }

    public function addInterface(ClassmapRelation $className): void
    {
        $this->interfacesImplemented[] = $className;
    }

    public function addTrait(ClassmapRelation $className): void
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

    /** @return  ClassmapRelation[] */
    public function getDependencies(): array
    {
        return $this->classesDepended;
    }

    /** @return  ClassmapRelation|null */
    public function getParent(): ?ClassmapRelation
    {
        return $this->classExtended;
    }

    /** @return  ClassmapRelation[] */
    public function getInterfaces(): array
    {
        return $this->interfacesImplemented;
    }

    /** @return  ClassmapRelation[] */
    public function getTraits(): array
    {
        return $this->includedTraits;
    }
}
