<?php

namespace Tests\PhpAT\functional\php81\fixtures;

use Tests\PhpAT\functional\php81\fixtures\AnotherNamespace\AnotherSimpleClass;
use Tests\PhpAT\functional\php81\fixtures\AnotherNamespace\SimpleClass;

class ClassWithNewFeatures
{
    public function __construct(
        public readonly EnumClassOne $status
    ) {}

    public function someMethod(SimpleClass&AnotherSimpleClass $value): string
    {
        return "Wow!";
    }
}