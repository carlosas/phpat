<?php declare(strict_types=1);

namespace Tests\PHPat\unit\selectors;

use PHPat\Selector\ClassExtends;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @covers \PHPat\Selector\ClassExtends
 */
class ClassExtendsTest extends TestCase
{
    public function testMatchesParent(): void
    {
        $selector = new ClassExtends('App\Base', false);
        $classReflection = $this->createMock(\ReflectionClass::class);

        $parent = $this->createMock(\ReflectionClass::class);
        $parent->method('getName')->willReturn('App\Base');
        $parent->method('getParentClass')->willReturn(false);

        $classReflection->method('getParentClass')->willReturn($parent);

        self::assertTrue($selector->matches($classReflection));
    }

    public function testMatchesGrandParent(): void
    {
        $selector = new ClassExtends('App\Root', false);
        $classReflection = $this->createMock(\ReflectionClass::class);

        $parent = $this->createMock(\ReflectionClass::class);
        $parent->method('getName')->willReturn('App\Base');

        $grandParent = $this->createMock(\ReflectionClass::class);
        $grandParent->method('getName')->willReturn('App\Root');
        $grandParent->method('getParentClass')->willReturn(false);

        $parent->method('getParentClass')->willReturn($grandParent);
        $classReflection->method('getParentClass')->willReturn($parent);

        self::assertTrue($selector->matches($classReflection));
    }

    public function testDoesNotMatchUnrelated(): void
    {
        $selector = new ClassExtends('App\Base', false);
        $classReflection = $this->createMock(\ReflectionClass::class);
        $classReflection->method('getParentClass')->willReturn(false);

        self::assertFalse($selector->matches($classReflection));
    }
}
