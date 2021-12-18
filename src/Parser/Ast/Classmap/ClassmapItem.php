<?php

namespace PhpAT\Parser\Ast\Classmap;

final class ClassmapItem
{
    private string $pathname;
    private string $classType;
    private ?int $flag;
    private ?ClassmapRelation $classExtended = null;
    /** @var array<ClassmapRelation> */
    private $classesDepended = [];
    /** @var array<ClassmapRelation> */
    private array $interfacesImplemented = [];
    /** @var array<ClassmapRelation> */
    private array $includedTraits = [];

    public function __construct(string $pathname, string $classType, ?int $flag)
    {
        $this->pathname = $pathname;
        $this->classType = $classType;
        $this->flag = $flag;
    }

    public function addDependency(ClassmapRelation $relation): void
    {
        $this->classesDepended[] = $relation;
    }

    public function addParent(ClassmapRelation $relation): void
    {
        $this->classExtended = $relation;
    }

    public function addInterface(ClassmapRelation $relation): void
    {
        $this->interfacesImplemented[] = $relation;
    }

    public function addTrait(ClassmapRelation $relation): void
    {
        $this->includedTraits[] = $relation;
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

    /** @return array<ClassmapRelation> */
    public function getDependencies(): array
    {
        return $this->classesDepended;
    }

    /** @return ClassmapRelation|null */
    public function getParent(): ?ClassmapRelation
    {
        return $this->classExtended;
    }

    /** @return array<ClassmapRelation> */
    public function getInterfaces(): array
    {
        return $this->interfacesImplemented;
    }

    /** @return array<ClassmapRelation> */
    public function getTraits(): array
    {
        return $this->includedTraits;
    }
}
