<?php declare(strict_types=1);

namespace PHPat\Selector;

use PHPStan\Reflection\ClassReflection;

final class Filepath implements SelectorInterface
{
    private string $filename;
    private bool $isRegex;

    public function __construct(string $filename, bool $isRegex)
    {
        $this->filename = $filename;
        $this->isRegex = $isRegex;
    }

    public function getName(): string
    {
        return $this->filename;
    }

    public function matches(ClassReflection $classReflection): bool
    {
        $filename = $classReflection->getFileName();

        if ($filename === null) {
            return false;
        }

        if ($this->isRegex) {
            return preg_match($this->filename, $filename) === 1;
        }

        return $filename === \trimSeparators($this->filename);
    }
}
