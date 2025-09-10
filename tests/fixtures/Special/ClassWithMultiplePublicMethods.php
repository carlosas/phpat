<?php declare(strict_types=1);

namespace Tests\PHPat\fixtures\Special;

class ClassWithMultiplePublicMethods
{
    public function __construct()
    {
        // Constructor should be ignored
    }

    public function targetMethod(): bool
    {
        return true;
    }

    public function anotherMethod(): bool
    {
        return false;
    }

    public function yetAnotherMethod(): string
    {
        return 'test';
    }
}