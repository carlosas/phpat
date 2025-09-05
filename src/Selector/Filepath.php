<?php declare(strict_types=1);

namespace PHPat\Selector;

use PHPStan\Reflection\ClassReflection;

final class Filepath implements SelectorInterface
{
    private string $filepath;
    private bool $isRegex;

    public function __construct(string $filepath, bool $isRegex)
    {
        $this->filepath = $filepath;
        $this->isRegex = $isRegex;
    }

    public function getName(): string
    {
        return $this->filepath;
    }

    public function matches(ClassReflection $classReflection): bool
    {
        $filepath = $classReflection->getFileName();

        if ($filepath === null) {
            return false;
        }

        if ($this->isRegex) {
            return preg_match($this->filepath, $filepath) === 1;
        }

        return $filepath === \trimSeparators($this->filepath);
    }
}
