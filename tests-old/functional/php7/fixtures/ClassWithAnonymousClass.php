<?php

namespace Tests\PHPat\unit\php7\fixtures;

class ClassWithAnonymousClass
{
    public function a()
    {
        $b = new class () extends SimpleClass implements SimpleInterface {
            public function c()
            {
                return new AnotherSimpleClass();
            }
        };
    }
}
