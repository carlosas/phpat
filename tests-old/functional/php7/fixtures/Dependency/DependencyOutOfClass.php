<?php

namespace Tests\PHPat\unit\php7\fixtures\Dependency;

if ('hello' === \Tests\PHPat\unit\php7\fixtures\SimpleClass::class) {
    echo 'bye';
}

class DependencyOutOfClass
{
}
