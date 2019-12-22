<?php

declare(strict_types=1);

namespace PhpAT\Rule\Type;

interface RuleType
{
    public function validate(
        string $fqcnOrigin,
        string $fqcnDestination,
        array $astMap,
        bool $inverse = false
    ): void;
}
