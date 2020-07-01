<?php

namespace PhpAT\Parser\Ast;

interface ClassLike
{
    public function matches(string $name): bool;

    public function toString(): string;
}
