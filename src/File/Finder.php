<?php

namespace PHPArchiTest\File;

interface Finder
{
    public function find(string $filePath, string $fileName, array $onlyOrigin, array $excluded): array;
}
