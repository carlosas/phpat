<?php declare(strict_types=1);

namespace PhpAT\Parser\Ast\Operator;

use PhpAT\Parser\Ast\ClassLike;

final class OneOf implements ClassLike
{
    /** @var ClassLike */
    private array $constraints;

    public function __construct(
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

            if ($count > 1) {
                return false;
            }
        }

        return $count === 1;
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
        return 'one of '.implode(' or ', array_map(
            fn (ClassLike $constraint) => $constraint->toString(),
            $this->constraints,
        ));
    }
}
