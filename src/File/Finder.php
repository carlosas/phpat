<?php declare(strict_types=1);

namespace PhpAT\File;

interface Finder
{
    public function find(string $filePath, string $fileName, array $onlyOrigin, array $excluded): array;
}
