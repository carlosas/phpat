<?php

declare(strict_types=1);

namespace Tests\PHPat\fixtures\Special;

final class ClassWithTwoUnrelatedNamedMethods
{
    public function bar(): bool
    {
        return true;
    }

    public function foo(): bool
    {
        return true;
    }
}