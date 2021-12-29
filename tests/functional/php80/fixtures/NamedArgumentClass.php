<?php

namespace Tests\PhpAT\functional\php80\fixtures;

class NamedArgumentClass
{
    public function someMethod(): void
    {
        $a = new UnionClass(
            classFiveOrSix: new SimpleClassFive()
        );
    }
}
