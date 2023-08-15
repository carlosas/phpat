<?php declare(strict_types=1);

namespace PHPat\Selector\Modifier;

use PHPat\Selector\SelectorInterface;
use PHPStan\Reflection\ClassReflection;

final class AndModifier implements SelectorInterface
{
    /** @var array<SelectorInterface> */
    private array $selectors;

    public function __construct(SelectorInterface ...$selector)
    {
        $this->selectors = array_values($selector);
    }

    public function matches(ClassReflection $classReflection): bool
    {
        foreach ($this->selectors as $selector) {
            if (!$selector->matches($classReflection)) {
                return false;
            }
        }

        return true;
    }

    public function getName(): string
    {
        return implode(
            ':and:',
            array_map(fn (SelectorInterface $selector) => $selector->getName(), $this->selectors),
        );
    }
}
