<?php declare(strict_types=1);

namespace Tests\PHPat\fixtures\Special;

class ClassWithOnlyPublicMethodNamed
{
    public const CONSTANT = 'constant';
    public string $property = 'property';
    public string $anotherProperty;

    public function __construct()
    {
        $this->anotherProperty = 'anotherProperty';
    }

    public function methodWithName(): bool
    {
        return true;
    }
}
