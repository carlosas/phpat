<?php declare(strict_types=1);

namespace Tests\PHPat\fixtures\Special;

class ClassWithOnePublicMethod
{
    public const CONSTANT = 'constant';
    public string $property = 'property';
    public string $anotherProperty;

    public function __construct()
    {
        $this->anotherProperty = 'anotherProperty';
    }

    public function publicMethod(): bool
    {
        return true;
    }
}
