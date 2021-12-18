<?php

namespace Tests\PhpAT\functional\php8\fixtures;

class NamedArgumentClass
{
    public function someMethod(): void
    {
        $a = new UnionClass(
            classFiveOrSix: new SimpleClassFive()
        );
    }
}
