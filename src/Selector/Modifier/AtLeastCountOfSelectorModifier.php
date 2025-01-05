<?php declare(strict_types=1);

namespace PHPat\Selector\Modifier;

use PHPat\Selector\SelectorInterface;
use PHPStan\Reflection\ClassReflection;

final class AtLeastCountOfSelectorModifier implements SelectorInterface
{
    private int $count;

    /** @var array<SelectorInterface> */
    private array $selectors;

    public function __construct(int $count, SelectorInterface ...$selectors)
    {
        $this->count = $count;
        $this->selectors = $selectors;
    }

    public function getName(): string
    {
        return \sprintf(
            'at least %d of %s',
            $this->count,
            \implode(' and ', \array_map(static fn ($selector) => $selector->getName(), $this->selectors)),
        );
    }

    public function matches(ClassReflection $classReflection): bool
    {
        $matches = 0;
        foreach ($this->selectors as $selector) {
            if ($selector->matches($classReflection)) {
                ++$matches;
            }

            if ($matches >= $this->count) {
                return true;
            }
        }

        return $matches >= $this->count;
    }
}
