<?php

declare(strict_types=1);

namespace Tests\PHPat\functional\fixtures;

class ClassWithAnonymousClass
{
    public function a(): void
    {
        $b = new class () extends SimpleClass implements SimpleInterface {
            public function c()
            {
                return new AnotherSimpleClass();
            }
        };
    }
}
