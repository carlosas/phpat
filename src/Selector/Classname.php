<?php declare(strict_types=1);

namespace PHPat\Selector;

final class Classname implements SelectorInterface
{
    private string $classname;
    private bool $isRegex;

    /**
     * @param class-string|string $classname
     */
    public function __construct(string $classname, bool $isRegex)
    {
        $this->classname = $classname;
        $this->isRegex = $isRegex;
    }

    public function getName(): string
    {
        return $this->classname;
    }

    /**
     * @param \ReflectionClass<object> $classReflection
     */
    public function matches(\ReflectionClass $classReflection): bool
    {
        if ($this->isRegex) {
            return preg_match($this->classname, $classReflection->getName()) === 1;
        }

        return $classReflection->getName() === \trimSeparators($this->classname);
    }
}
