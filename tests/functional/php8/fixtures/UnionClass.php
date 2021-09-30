<?php

namespace Tests\PhpAT\functional\php8\fixtures;

class UnionClass
{
    private int|float $foo;
    private $someClass;

    public function __construct(SimpleClassFive|SimpleClassSix $classFiveOrSix)
    {
        $this->someClass = $classFiveOrSix;
    }

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
        //not implemented
    }
}
