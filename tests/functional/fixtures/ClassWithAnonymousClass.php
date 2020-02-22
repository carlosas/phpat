<?php

namespace Tests\PhpAT\functional\fixtures;

class ClassWithAnonymousClass
{
    public function a() {
        $b = new class extends SimpleClass implements SimpleInterface {
            public function c() {
                return new AnotherSimpleClass();
            }
        };
    }
}
