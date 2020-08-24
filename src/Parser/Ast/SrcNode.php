<?php

namespace PhpAT\Parser\Ast;

use PhpAT\Parser\Ast\FullClassName;
use PhpAT\Parser\Relation\AbstractRelation;

class SrcNode implements \JsonSerializable
{
    /**
     * @var string
     */
    private $filePathname;
    /**
     * @var string
     */
    private $className;
    /**
     * @var AbstractRelation[]
     */
    private $relations;

    public function __construct(
        string $fileName,
        FullClassName $className,
        array $relations
    ) {
        $this->filePathname = $fileName;
        $this->className = $className->getFQCN();
        $this->relations = $relations;
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @return string
     */
    public function getFilePathname(): string
    {
        return $this->filePathname;
    }

    /**
     * @return AbstractRelation[]
     */
    public function getRelations(): array
    {
        return $this->relations;
    }

    public function jsonSerialize(): array
    {
        return [
            'pathname' => $this->getFilePathname(),
            'classname' => $this->getClassName(),
            'relations' => $this->getRelations(),
        ];
    }
}
