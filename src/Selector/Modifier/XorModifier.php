<?php declare(strict_types=1);

namespace PHPat\Selector\Modifier;

use PHPat\Selector\SelectorInterface;
use PHPStan\Reflection\ClassReflection;

final class XorModifier implements SelectorInterface
{
    /** @var array<SelectorInterface> */
    private array $selectors;

    public function __construct(SelectorInterface ...$selector)
    {
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

        return $matches === 1;
    }

    public function getName(): string
    {
        return implode(
            ':xor:',
            array_map(static fn (SelectorInterface $selector) => $selector->getName(), $this->selectors),
        );
    }
}
