<?php

namespace Tests\PhpAT\functional\php81\fixtures;

use Tests\PhpAT\functional\php81\fixtures\AnotherNamespace\EnumClassThree;
use Tests\PhpAT\functional\php81\fixtures\AnotherNamespace\EnumClassTwo;

class ClassUsingEnum
{
    private EnumClassThree $three;

    public function someMethod(EnumClassOne $optionOne): void
    {
        $option = EnumClassTwo::Option1;
    }
}
