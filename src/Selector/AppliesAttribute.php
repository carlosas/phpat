<?php declare(strict_types=1);

namespace PHPat\Selector;

final class AppliesAttribute implements SelectorInterface
{
    private string $classname;
    private bool $isRegex;

    /** @var array<string, mixed> */
    private array $arguments;

    /**
     * @param class-string|string  $classname
     * @param array<string, mixed> $arguments
     */
    public function __construct(string $classname, bool $isRegex = false, array $arguments = [])
    {
        $this->classname = $classname;
        $this->isRegex = $isRegex;
        $this->arguments = $arguments;
    }

    public function getName(): string
    {
        return $this->classname;
    }

    public function matches(\ReflectionClass $classReflection): bool
    {
        /** @var list<\ReflectionAttribute<object>> $attributes */
        $attributes = $classReflection->getAttributes();

        if ($this->isRegex) {
            return $this->matchesRegex($attributes);
        }

        foreach ($attributes as $attribute) {
            if ($attribute->getName() === $this->classname) {
                $arguments = $attribute->getArguments();

                if (count($this->arguments) > 0) {
                    return $this->matchesArguments($arguments);
                }

                return true;
            }
        }

        return false;
    }

    /**
     * @param list<\ReflectionAttribute<object>> $attributes
     */
    private function matchesRegex(array $attributes): bool
    {
        /** @var \ReflectionAttribute<object> $attribute */
        foreach ($attributes as $attribute) {
            if (preg_match($this->classname, $attribute->getName()) === 1) {
                $arguments = $attribute->getArguments();

                if (count($this->arguments) > 0) {
                    return $this->matchesArguments($arguments);
                }

                return true;
            }
        }

        return false;
    }

    /**
     * @param array<int|string, mixed> $arguments
     */
    private function matchesArguments(array $arguments): bool
    {
        foreach ($this->arguments as $key => $value) {
            if (!array_key_exists($key, $arguments) || $arguments[$key] !== $value) {
                return false;
            }
        }

        return true;
    }
}
