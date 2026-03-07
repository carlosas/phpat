<?php declare(strict_types=1);

namespace Tests\PHPat\unit\selectors;

use PHPat\Selector\IsInterface;

/**
 * @internal
 *
 * @covers \PHPat\Selector\IsInterface
 */
class IsInterfaceTest extends SelectorTestCase
{
    public function testMatchesInterface(): void
    {
        $selector = new IsInterface();
        $classReflection = $this->getReflectionClass(InterfaceDummyClassValid::class);

        self::assertTrue($selector->matches($classReflection));
    }

    public function testDoesNotMatchClass(): void
    {
        $selector = new IsInterface();
        $classReflection = $this->getReflectionClass(DummyClassInvalid::class);

        self::assertFalse($selector->matches($classReflection));
    }
}
