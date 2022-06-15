<?php declare(strict_types=1);

namespace PhpAT\Parser\Ast\Operator;

use PhpAT\Parser\Ast\ClassLike;

final class AtMostCountOf implements ClassLike
{
    /** @var ClassLike */
    private array $constraints;

    public function __construct(
        private int $threshold,
        ClassLike ...$constraints,
    ) {
        $this->constraints = $constraints;
    }

    public function matches(string $name): bool
    {
        $count = 0;
        foreach ($this->constraints as $constraint) {
            if ($constraint->matches($name) === true) {
                $count++;
            }
            if ($count > $this->threshold) {
                return false;
            }
        }

        return $count <= $this->threshold;
    }

    public function getMatchingNodes(array $nodes): array
    {
        return array_merge(
            [],
            ...array_map(
                fn (ClassLike $constraint) => $constraint->getMatchingNodes($nodes),
                $this->constraints,
            )
        );
    }

    public function toString(): string
    {
        return 'at most one of '.implode(' and ', array_map(
            fn (ClassLike $constraint) => $constraint->toString(),
            $this->constraints,
        ));
    }
}
