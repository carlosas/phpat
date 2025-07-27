<?php declare(strict_types=1);

namespace Tests\PHPat\fixtures\Special;

class ClassExtendingException extends \Exception
{
    public function customMethod(): string
    {
        return 'custom';
    }
}

class ClassExtendingError extends \Error
{
    public function customMethod(): string
    {
        return 'custom';
    }
}