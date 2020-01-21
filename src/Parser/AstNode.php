<?php

namespace PhpAT\Parser;

class AstNode implements \JsonSerializable
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
    private $assertion;

    public function __construct(
        \SplFileInfo $fileInfo,
        ClassName $className,
        ?ClassName $parent,
        array $dependencies,
        array $interfaces,
        array $mixins
    ) {
        $this->filePathname = $this->normalizePathname($fileInfo->getPathname());
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

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @return string[]
     */
    public function getDependencies(): array
    {
        return $this->dependencies ?? [];
    }

    /**
     * @return string[]
     */
    public function getInterfaces(): array
    {
        return $this->interfaces ?? [];
    }

    /**
     * @return string
     */
    public function getParent(): string
    {
        return $this->parent ?? '';
    }

    /**
     * @return string
     */
    public function getFilePathname(): string
    {
        return $this->filePathname;
    }

    /**
     * @return string[]
     */
    public function getMixins(): array
    {
        return $this->mixins ?? [];
    }

    public function jsonSerialize(): array
    {
        return [
            'pathname' => $this->getFilePathname(),
            'classname' => $this->getClassName(),
            'parent' => $this->getParent(),
            'dependencies' => $this->getDependencies(),
            'interfaces' => $this->getInterfaces(),
        ];
    }

    private function normalizePathname(string $pathname): string
    {
        return str_replace('\\', '/', $pathname);
    }
}
