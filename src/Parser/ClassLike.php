<?php

namespace PhpAT\Parser;

interface ClassLike
{
    public function matches(string $name): bool;

    public function toString(): string;
}
