<?php

declare(strict_types=1);

namespace Tests\PHPat\fixtures;

use Tests\PHPat\fixtures\Simple\AbstractClass;
use Tests\PHPat\fixtures\Simple\SimpleClass;
use Tests\PHPat\fixtures\Simple\SimpleInterface;
use Tests\PHPat\fixtures\Simple\SimpleTrait;
use Tests\PHPat\fixtures\Special\ClassWithConstant;

class SuperClass extends AbstractClass implements SimpleInterface
{
    use SimpleTrait;

    private SimpleInterface $simple;

    public function __construct(SimpleInterface $simple)
    {
        $this->simple = $simple;
    }

    public function getSimple(): SimpleInterface
    {
        return $this->simple;
    }

    public function getNewSimpleClass(): SimpleClass
    {
        return new SimpleClass();
    }

    public function usingConstant(): string
    {
        return ClassWithConstant::CONSTANT;
    }
}
