<?php

namespace Tests\PHPat\unit\php81\fixtures;

use Tests\PHPat\unit\php81\fixtures\AnotherNamespace\EnumClassThree;
use Tests\PHPat\unit\php81\fixtures\AnotherNamespace\EnumClassTwo;

class ClassUsingEnum
{
    private EnumClassThree $three;

    public function someMethod(EnumClassOne $optionOne): void
    {
        $option = EnumClassTwo::Option1;
    }
}
