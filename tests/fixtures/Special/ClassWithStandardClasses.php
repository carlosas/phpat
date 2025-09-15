<?php declare(strict_types=1);

namespace Tests\PHPat\fixtures\Special;

class ClassWithStandardClasses
{
    public function createException(): \Exception
    {
        return new \Exception('test');
    }

    public function createStdClass(): \stdClass
    {
        return new \stdClass();
    }

    public function createError(): \Error
    {
        return new \Error('test');
    }

    public function createClosure(): \Closure
    {
        return function() { return 'test'; };
    }
}