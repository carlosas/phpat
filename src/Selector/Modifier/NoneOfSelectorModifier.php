<?php declare(strict_types=1);

namespace PHPat\Selector\Modifier;

use PHPat\Selector\SelectorInterface;
use PHPStan\Reflection\ClassReflection;

final class NoneOfSelectorModifier implements SelectorInterface
{
    /** @var array<SelectorInterface> */
    private array $selectors;

    public function __construct(SelectorInterface ...$selectors)
    {
        $this->selectors = $selectors;
    }

    #[\Override]
    public function getName(): string
    {
        return \sprintf(
            'none of: %s',
            \implode(' and ', \array_map(static fn ($selector) => $selector->getName(), $this->selectors)),
        );
    }

    #[\Override]
    public function matches(ClassReflection $classReflection): bool
    {
        foreach ($this->selectors as $selector) {
            if ($selector->matches($classReflection)) {
                return false;
            }
        }

        return true;
    }
}
