<?php

namespace Tests\PhpAT\functional\fixtures\Dependency;

if ('hello' === \Tests\PhpAT\functional\php7\fixtures\SimpleClass::class) {
    echo 'bye';
}

class DependencyOutOfClass
{
}