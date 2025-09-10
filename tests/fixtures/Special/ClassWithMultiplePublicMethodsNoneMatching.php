<?php declare(strict_types=1);

namespace Tests\PHPat\fixtures\Special;

class ClassWithMultiplePublicMethodsNoneMatching
{
    public function __construct()
    {
        // Constructor should be ignored
    }

    public function firstMethod(): bool
    {
        return true;
    }

    public function secondMethod(): bool
    {
        return false;
    }
}