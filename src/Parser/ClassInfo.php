<?php

namespace PhpAT\Parser;

class ClassInfo implements \JsonSerializable
{
    /**
     * @var string
     */
    private $className;
    /**
     * @var string[]
     */
    private $dependencies;
    /**
     * @var string[]
     */
    private $interfaces;
    /**
     * @var string
     */
    private $parent;
    /**
     * @var string
     */
    private $filePathname;
    /**
     * @var string[]
     */
    private $mixins;
    /**
     * @var string
     */
    private $type;

    public function __construct(
        \SplFileInfo $fileInfo,
        ClassName $className,
        ?ClassName $parent,
        array $dependencies,
        array $interfaces,
        array $mixins
    ) {
        $this->filePathname = $fileInfo->getPathname();
        $this->className = $className->getFQCN();
        $this->parent = $parent === null ? null : $parent->getFQCN();
        foreach ($dependencies as $cn) {
            $this->dependencies[] = $cn->getFQCN();
        }
        foreach ($interfaces as $cn) {
            $this->interfaces[] = $cn->getFQCN();
        }
        foreach ($mixins as $cn) {
            $this->mixins[] = $cn->getFQCN();
        }
    }

    public function jsonSerialize(): array
    {
        return [
            'pathname' => $this->filePathname,
            'classname' => $this->className,
            'parent' => $this->parent,
            'dependencies' => $this->dependencies,
            'interfaces' => $this->interfaces,
        ];
    }
}
