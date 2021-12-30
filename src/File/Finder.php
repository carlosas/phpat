<?php

namespace PhpAT\File;

interface Finder
{
    /**
     * @return array<\SplFileInfo>
     */
    public function findFiles(string $filePath, string $fileName, array $exclude): array;

    public function locateFile(string $filePath, string $fileName, array $exclude): ?\SplFileInfo;
}
