<?php

declare(strict_types=1);

namespace Tests\PHPat\Fixtures;

use Tests\PHPat\Fixtures\Simple\SimpleAbstractClass;
use Tests\PHPat\Fixtures\Simple\SimpleAttribute;
use Tests\PHPat\Fixtures\Simple\SimpleClass;
use Tests\PHPat\Fixtures\Simple\SimpleClassFive;
use Tests\PHPat\Fixtures\Simple\SimpleClassFour;
use Tests\PHPat\Fixtures\Simple\SimpleClassSix;
use Tests\PHPat\Fixtures\Simple\SimpleClassThree;
use Tests\PHPat\Fixtures\Simple\SimpleClassTwo;
use Tests\PHPat\Fixtures\Simple\SimpleException;
use Tests\PHPat\Fixtures\Simple\SimpleInterface;
use Tests\PHPat\Fixtures\Simple\SimpleInterfaceTwo;
use Tests\PHPat\Fixtures\Simple\SimpleTrait;
use Tests\PHPat\Fixtures\Special\ClassImplementing;
use Tests\PHPat\Fixtures\Special\ClassWithConstant;
use Tests\PHPat\Fixtures\Special\ClassWithStaticMethod;
use Tests\PHPat\Fixtures\Special\InterfaceWithTemplate;

/**
 * @property SimpleClass $myProperty
 * @property-read SimpleClassTwo $myProperty2
 * @property-write SimpleClassThree $myProperty3
 * @method SimpleClassFour someMethod(SimpleClassFive $m)
 * @mixin SimpleClassSix
 */
#[SimpleAttribute]
class FixtureClass extends SimpleAbstractClass implements SimpleInterface
{
    use SimpleTrait;

    #[SimpleAttribute]
    private const CONSTANT = 'constant';

    #[SimpleAttribute]
    private SimpleInterface $simple;
    private SimpleInterfaceTwo $simple2;

    public function __construct(#[SimpleAttribute] SimpleInterface $simple)
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

    public function usingStaticMethod(): void
    {
        ClassWithStaticMethod::staticMethod();
    }

    /**
     * @param SimpleClass $p
     * @param SimpleClassTwo $p2 Parameter with description
     * @param \Tests\PHPat\Fixtures\Simple\SimpleClassThree $p3
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

    #[SimpleAttribute]
    public function methodWithAttribute(): bool
    {
        return true;
    }
}
