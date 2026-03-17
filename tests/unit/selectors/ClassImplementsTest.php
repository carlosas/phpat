<?php declare(strict_types=1);

namespace Tests\PHPat\unit\selectors;

use PHPat\Selector\ClassImplements;

/**
 * @internal
 *
 * @covers \PHPat\Selector\ClassImplements
 */
class ClassImplementsTest extends SelectorTestCase
{
    public function testMatchesInterface(): void
    {
        $selector = new ClassImplements(InterfaceDummyClassValid::class, false);
        $classReflection = $this->getReflectionClass(ImplementsDummyClassValid::class);

        self::assertTrue($selector->matches($classReflection));
    }

    public function testDoesNotMatchInterface(): void
    {
        $selector = new ClassImplements(InterfaceDummyClassValid::class, false);
        $classReflection = $this->getReflectionClass(DummyClassValid::class);

        self::assertFalse($selector->matches($classReflection));
    }

    public function testMatchesRegex(): void
    {
        $selector = new ClassImplements('/DummyClassValid$/', true);
        $classReflection = $this->getReflectionClass(ImplementsDummyClassValid::class);

        self::assertTrue($selector->matches($classReflection));
    }
}
