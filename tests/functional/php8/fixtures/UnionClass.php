<?php

namespace Tests\PhpAT\functional\fixtures;

class UnionClass
{
    private int|float $foo;

    public function setter(float|int $foo): void
    {
        $this->foo = $foo;
    }

    public function squareAndAdd(float|int $bar): int|float
    {
        return $bar ** 2 + $this->foo;
    }
}
