<?php declare(strict_types=1);

namespace Tests\PHPat\fixtures\Special;

class ClassWithNoPublicMethods
{
    public function __construct()
    {
        // Constructor should be ignored
    }

    private function privateMethod(): bool
    {
        return true;
    }

    protected function protectedMethod(): bool
    {
        return false;
    }
}