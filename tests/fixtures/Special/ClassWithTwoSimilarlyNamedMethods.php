<?php

declare(strict_types=1);

namespace Tests\PHPat\fixtures\Special;

final class ClassWithTwoSimilarlyNamedMethods
{
    public function exampleOne(): bool
    {
        return true;
    }

    public function exampleTwo(): bool
    {
        return true;
    }
}