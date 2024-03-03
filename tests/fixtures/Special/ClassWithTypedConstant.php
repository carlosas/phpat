<?php declare(strict_types=1);

namespace Tests\PHPat\fixtures\Special;

use Tests\PHPat\fixtures\Simple\SimpleClass;

if (PHP_VERSION_ID < 80300) {
    return;
}

/*
 * WARNING: This file is excluded from the composer autoload.
 */
class ClassWithTypedConstant
{
    public const ?SimpleClass CONSTANT = null;
}
