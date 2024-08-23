<?php declare(strict_types=1);

namespace PHPat\Selector\Modifier;

use PHPat\Selector\SelectorInterface;
use PHPStan\Reflection\ClassReflection;

final class AtLeastModifier implements SelectorInterface
{
    private int $min;
    private array $selectors;

    public function __construct(int $min, SelectorInterface ...$selector)
    {
        $this->min = $min;
        $this->selectors = array_values($selector);
    }

    public function matches(ClassReflection $classReflection): bool
    {
        $matches = 0;

        foreach ($this->selectors as $selector) {
            if ($selector->matches($classReflection)) {
                ++$matches;
            }
        }

        return $matches >= $this->min;
    }

    public function getName(): string
    {
        return 'at-least-'.$this->min.'-of: '.implode(
            ', ',
            array_map(static fn (SelectorInterface $selector) => $selector->getName(), $this->selectors),
        );
    }
}
