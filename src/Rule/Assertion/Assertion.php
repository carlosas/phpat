<?php

declare(strict_types=1);

namespace PhpAT\Rule\Assertion;

interface Assertion
{
    public function validate(
        string $fqcnOrigin,
        array $fqcnDestinations,
        array $astMap,
        bool $inverse = false
    ): void;
}
