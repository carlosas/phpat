<?php declare(strict_types=1);

namespace PhpAT\Selector\Operator;

use PhpAT\Parser\Ast\ClassLike;
use PhpAT\Parser\Ast\Operator\AllOf;
use PhpAT\Parser\Ast\Operator\AnyOf;
use PhpAT\Parser\Ast\ReferenceMap;
use PhpAT\Selector\SelectorInterface;

final class AnyOfSelector implements SelectorInterface
{
    /** @var list<SelectorInterface> */
    private array $selectors;

    public function __construct(SelectorInterface ...$selectors)
    {
        $this->selectors = $selectors;
    }

    public function getDependencies(): array
    {
        return array_merge(
            ...array_map(fn (SelectorInterface $selector) => $selector->getDependencies(), $this->selectors)
        );
    }

    public function injectDependencies(array $dependencies): void
    {
        array_walk($this->selectors, fn (SelectorInterface $selector) => $selector->injectDependencies($dependencies));
    }

    public function setReferenceMap(ReferenceMap $map): void
    {
        array_walk($this->selectors, fn (SelectorInterface $selector) => $selector->setReferenceMap($map));
    }

    /**
     * @return array<ClassLike>
     */
    public function select(): array
    {
        return [
            new AnyOf(
                ...array_map(fn (SelectorInterface $selector) => new AllOf(...$selector->select()), $this->selectors)
            ),
        ];
    }

    public function getParameter(): string
    {
        return implode(' or ', array_map(fn (SelectorInterface $selector) => $selector->getParameter(), $this->selectors));
    }
}
