<?php

declare(strict_types=1);

namespace Tests\PHPat\unit\fixtures\Dependency;

if ('hello' === \Tests\PHPat\unit\php7\fixtures\SimpleClass::class) {
    echo 'bye';
}

class DependencyOutOfClass
{
}
