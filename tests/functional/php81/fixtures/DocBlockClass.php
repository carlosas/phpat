<?php

namespace Tests\PhpAT\functional\php81\fixtures;

use Tests\PhpAT\functional\php81\fixtures\AnotherNamespace\AnotherSimpleClass;
use Tests\PhpAT\functional\php81\fixtures\AnotherNamespace\SimpleClass;

class DocBlockClass
{
    /**
     * @param SimpleClass&AnotherSimpleClass $value
     */
    public function someMethod($value): string
    {
        return "Wow!";
    }
}
