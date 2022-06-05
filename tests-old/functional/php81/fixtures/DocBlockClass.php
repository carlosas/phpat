<?php

namespace Tests\PHPat\unit\php81\fixtures;

use Tests\PHPat\unit\php81\fixtures\AnotherNamespace\AnotherSimpleClass;
use Tests\PHPat\unit\php81\fixtures\AnotherNamespace\SimpleClass;

class DocBlockClass
{
    /**
     * @param AnotherSimpleClass&SimpleClass $value
     */
    public function someMethod($value): string
    {
        return "Wow!";
    }
}
