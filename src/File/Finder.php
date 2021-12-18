<?php

namespace PhpAT\File;

interface Finder
{
    /**
     * @return array<\SplFileInfo>
     */
    public function find(string $filePath, string $fileName, array $include, array $exclude): array;

    public function locateFile(string $filePath, string $fileName): ?\SplFileInfo;
}
