<?php

declare(strict_types=1);

namespace PhpAT\Rule\Assertion;

use PhpAT\Parser\ClassLike;

interface Assertion
{
    /**
     * @param ClassLike   $origin
     * @param ClassLike[] $destinations
     * @param array       $astMap
     * @param bool        $inverse
     */
    public function validate(
        ClassLike $origin,
        array $destinations,
        array $astMap,
        bool $inverse = false
    ): void;
}
