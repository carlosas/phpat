<?php declare(strict_types=1);

namespace Tests\PHPat\unit\selectors;

use PHPat\Selector\ClassExtends;

/**
 * @internal
 *
 * @covers \PHPat\Selector\ClassExtends
 */
class ClassExtendsTest extends SelectorTestCase
{
    public function testMatchesParent(): void
    {
        $selector = new ClassExtends(DummyClassValid::class, false);
        $classReflection = $this->getReflectionClass(ExtendsDummyClassValid::class);

        self::assertTrue($selector->matches($classReflection));
    }

    public function testMatchesGrandParent(): void
    {
        $selector = new ClassExtends(DummyClassValid::class, false);
        $classReflection = $this->getReflectionClass(GrandParentExtendsDummyClassValid::class);

        self::assertTrue($selector->matches($classReflection));
    }

    public function testDoesNotMatchUnrelated(): void
    {
        $selector = new ClassExtends(DummyClassValid::class, false);
        $classReflection = $this->getReflectionClass(DoesNotExtendDummyClassInvalid::class);

        self::assertFalse($selector->matches($classReflection));
    }
}
