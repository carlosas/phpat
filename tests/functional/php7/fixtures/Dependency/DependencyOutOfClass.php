<?php

namespace Tests\PhpAT\functional\php7\fixtures\Dependency;

if ('hello' === \Tests\PhpAT\functional\php7\fixtures\SimpleClass::class) {
    echo 'bye';
}

class DependencyOutOfClass
{
}
