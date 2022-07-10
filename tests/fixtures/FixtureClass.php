<?php

declare(strict_types=1);

namespace Tests\PHPat\fixtures;

use Tests\PHPat\fixtures\Simple\SimpleAbstractClass;
use Tests\PHPat\fixtures\Simple\SimpleClass;
use Tests\PHPat\fixtures\Simple\SimpleClassFive;
use Tests\PHPat\fixtures\Simple\SimpleClassFour;
use Tests\PHPat\fixtures\Simple\SimpleClassSix;
use Tests\PHPat\fixtures\Simple\SimpleClassThree;
use Tests\PHPat\fixtures\Simple\SimpleClassTwo;
use Tests\PHPat\fixtures\Simple\SimpleException;
use Tests\PHPat\fixtures\Simple\SimpleInterface;
use Tests\PHPat\fixtures\Simple\SimpleTrait;
use Tests\PHPat\fixtures\Special\ClassImplementing;
use Tests\PHPat\fixtures\Special\ClassWithConstant;
use Tests\PHPat\fixtures\Special\InterfaceWithTemplate;

/**
 * @property SimpleClass $myProperty
 * @property-write SimpleClassTwo $myProperty2
 * @property-read SimpleClassThree $myProperty3
 * @method SimpleClassFour someMethod(SimpleClassFive $m)
 * @mixin SimpleClassSix
 */
class FixtureClass extends SimpleAbstractClass implements SimpleInterface
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

    /**
     * @param SimpleClass $p
     * @param SimpleClassTwo $p2 Parameter with description
     * @param \Tests\PHPat\fixtures\Simple\SimpleClassThree $p3
     * @param array<SimpleClassFour> $p4
     * @param SimpleClassFive|SimpleClassSix $p5_6
     * @param InterfaceWithTemplate<ClassImplementing> $t
     * @throws SimpleException
     * @return SimpleInterface Some nice description here
     */
    public function methodWithDocBlocks($p, $p2, $p3, $p4, $p5_6, $t)
    {
        /** @var null|SimpleClass $v */
        $v = random_int(0, 1) > 0 ? $p : null;
        if ($v === null) {
            throw new SimpleException();
        }

        return new ClassImplementing();
    }
}
