<?php declare(strict_types=1);

namespace PHPat\Selector\Modifier;

use PHPat\Selector\SelectorInterface;

final class OneOfSelectorModifier implements SelectorInterface
{
    /** @var array<SelectorInterface> */
    private array $selectors;

    public function __construct(SelectorInterface ...$selectors)
    {
        $this->selectors = $selectors;
    }

    public function getName(): string
    {
        return \sprintf(
            'one of: [ %s ]',
            \implode(' and ', \array_map(static fn ($selector) => $selector->getName(), $this->selectors)),
        );
    }

    /**
     * @param \ReflectionClass<object> $classReflection
     */
    public function matches(\ReflectionClass $classReflection): bool
    {
        $matches = 0;
        foreach ($this->selectors as $selector) {
            if ($selector->matches($classReflection)) {
                ++$matches;
            }

            if ($matches > 1) {
                return false;
            }
        }

        return $matches === 1;
    }
}
