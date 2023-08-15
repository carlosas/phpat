<?php declare(strict_types=1);

namespace PHPat\Selector\Modifier;

use PHPat\Selector\SelectorInterface;
use PHPStan\Reflection\ClassReflection;

final class NotModifier implements SelectorInterface
{
    private SelectorInterface $selector;

    public function __construct(SelectorInterface $selector)
    {
        $this->selector = $selector;
    }

    public function matches(ClassReflection $classReflection): bool
    {
        return !$this->selector->matches($classReflection);
    }

    public function getName(): string
    {
        return $this->selector->getName().':not';
    }
}
