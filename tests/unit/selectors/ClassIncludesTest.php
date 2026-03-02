<?php declare(strict_types=1);

namespace Tests\PHPat\unit\selectors;

use PHPat\Selector\ClassIncludes;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @covers \PHPat\Selector\ClassIncludes
 */
class ClassIncludesTest extends TestCase
{
    public function testMatchesTrait(): void
    {
        $selector = new ClassIncludes('App\MyTrait', false);
        $classReflection = $this->createMock(\ReflectionClass::class);

        $trait = $this->createMock(\ReflectionClass::class);
        $trait->method('getName')->willReturn('App\MyTrait');
        $trait->method('getTraits')->willReturn([]);

        $classReflection->method('getTraits')->willReturn(['App\MyTrait' => $trait]);
        $classReflection->method('getParentClass')->willReturn(false);

        self::assertTrue($selector->matches($classReflection));
    }

    public function testMatchesTraitInParent(): void
    {
        $selector = new ClassIncludes('App\MyTrait', false);
        $classReflection = $this->createMock(\ReflectionClass::class);
        $classReflection->method('getTraits')->willReturn([]);

        $parent = $this->createMock(\ReflectionClass::class);
        $trait = $this->createMock(\ReflectionClass::class);
        $trait->method('getName')->willReturn('App\MyTrait');
        $trait->method('getTraits')->willReturn([]);

        $parent->method('getTraits')->willReturn(['App\MyTrait' => $trait]);
        $parent->method('getParentClass')->willReturn(false);

        $classReflection->method('getParentClass')->willReturn($parent);

        self::assertTrue($selector->matches($classReflection));
    }
}
