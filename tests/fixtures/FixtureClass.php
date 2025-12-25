<?php declare(strict_types=1);

namespace Tests\PHPat\fixtures;

use Tests\PHPat\fixtures\Simple\SimpleAbstractClass;
use Tests\PHPat\fixtures\Simple\SimpleAttribute;
use Tests\PHPat\fixtures\Simple\SimpleClass;
use Tests\PHPat\fixtures\Simple\SimpleClassFive;
use Tests\PHPat\fixtures\Simple\SimpleClassFour;
use Tests\PHPat\fixtures\Simple\SimpleClassSix;
use Tests\PHPat\fixtures\Simple\SimpleClassThree;
use Tests\PHPat\fixtures\Simple\SimpleClassTwo;
use Tests\PHPat\fixtures\Simple\SimpleException;
use Tests\PHPat\fixtures\Simple\SimpleInterface;
use Tests\PHPat\fixtures\Simple\SimpleInterfaceTwo;
use Tests\PHPat\fixtures\Simple\SimpleTrait;
use Tests\PHPat\fixtures\Special\ClassImplementing;
use Tests\PHPat\fixtures\Special\ClassWithConstant;
use Tests\PHPat\fixtures\Special\ClassWithStaticMethod;
use Tests\PHPat\fixtures\Special\InterfaceWithTemplate;

/**
 * @property SimpleClass      $myProperty
 * @property SimpleClassTwo   $myProperty2
 * @property SimpleClassThree $myProperty3
 * @method   SimpleClassFour  someMethod(SimpleClassFive $m)
 * @mixin SimpleClassSix
 */
#[SimpleAttribute(something: 'something')]
class FixtureClass extends SimpleAbstractClass implements SimpleInterface
{
    use SimpleTrait;
    #[SimpleAttribute] private const CONSTANT = 'constant';
    #[SimpleAttribute] private SimpleInterface $simple;
    private SimpleInterfaceTwo $simple2;

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

    public function usingStaticMethod(): void
    {
        ClassWithStaticMethod::staticMethod();
    }

    /**
     * @param  SimpleClass                                   $p
     * @param  SimpleClassTwo                                $p2 Parameter with description
     * @param  \Tests\PHPat\fixtures\Simple\SimpleClassThree $p3
     * @param  array<SimpleClassFour>                        $p4
     * @param  SimpleClassFive|SimpleClassSix                $p5_6
     * @param  InterfaceWithTemplate<ClassImplementing>      $t
     * @return SimpleInterface                               Some nice description here
     * @throws SimpleException
     */
    public function methodWithDocBlocks($p, $p2, $p3, $p4, $p5_6, $t)
    {
        /** @var null|SimpleClass $random */
        $random = (new \DateTime('now'))->getTimestamp() % 2 === 0 ? $p : null;
        if ($random) {
            throw new SimpleException();
        }

        return new ClassImplementing();
    }

    /**
     * @template T
     *
     * @param  class-string<T> $modelClass
     * @return T
     */
    public function doSomething(string $modelClass)
    {
        return new $modelClass();
    }

    #[SimpleAttribute(SimpleClass::class)]
    public function methodWithAllAttributes(#[SimpleAttribute] int $number): int
    {
        $fn1 = #[SimpleAttribute(new SimpleClass())] fn(int $a) => $a + 1;
        $fn2 = #[SimpleAttribute] fn(int $a) => $a + 1;
        $fn3 = #[SimpleAttribute] static fn(int $a) => $a + 1;
        $fn4 = #[SimpleAttribute] static fn(int $a) => $a + 1;

        return $fn1($number) + $fn2($number) + $fn3($number) + $fn4($number);
    }

    #[SimpleAttribute(new SimpleClass())]
    public function methodWithNewInAttribute(): void
    {
    }

    public function catchException(): void
    {
        try {
        } catch (SimpleException $e) {
        }
    }
}
