<?php

namespace Tests\PhpAT\functional\php8\fixtures;

class UnionClass
{
    private int|float $foo;
    private SimpleClassFive|SimpleClassSix $someClass;

    public function setter(float|int $foo): void
    {
        $this->foo = $foo;
    }

    public function squareAndAdd(float|int $bar): int|float
    {
        return $bar ** 2 + $this->foo;
    }

    public function someMethod(SimpleClassOne|SimpleClassTwo $bar): SimpleClassThree|SimpleClassFour
    {
        if ($bar instanceof SimpleClassOne) {
            return new SimpleClassThree();
        }

        if ($bar instanceof SimpleClassTwo) {
            return new SimpleClassFour();
        }
    }
}
