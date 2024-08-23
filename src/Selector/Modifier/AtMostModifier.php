<?php declare(strict_types=1);

namespace PHPat\Selector\Modifier;

use PHPat\Selector\SelectorInterface;
use PHPStan\Reflection\ClassReflection;

final class AtMostModifier implements SelectorInterface
{
    private int $max;
    private array $selectors;

    public function __construct(int $max, SelectorInterface ...$selector)
    {
        $this->max = $max;
        $this->selectors = array_values($selector);
    }

    public function matches(ClassReflection $classReflection): bool
    {
        $matches = 0;

        foreach ($this->selectors as $selector) {
            if ($selector->matches($classReflection)) {
                $matches++;
            }
        }

        return $matches <= $this->max;
    }

    public function getName(): string
    {
        return 'at-most-' . $this->max . '-of: ' . implode(
            ', ',
            array_map(static fn (SelectorInterface $selector) => $selector->getName(), $this->selectors),
        );
    }
}
