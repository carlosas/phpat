<?php

namespace Tests\PHPat\unit\php80\fixtures;

class NamedArgumentClass
{
    public function someMethod(): void
    {
        $a = new UnionClass(
            classFiveOrSix: new SimpleClassFive()
        );
    }
}
