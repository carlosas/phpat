<?php

namespace PhpAT\File;

interface Finder
{
    /**
     * @return \SplFileInfo[]
     */
    public function find(string $filePath, string $fileName, array $onlyOrigin, array $excluded): array;

    public function locateFile(string $filePath, string $fileName): ?\SplFileInfo;
}
