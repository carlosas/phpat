<?php declare(strict_types=1);

namespace Tests\PHPat\unit\selectors;

use PHPat\Selector\IsAbstract;

/**
 * @internal
 *
 * @covers \PHPat\Selector\IsAbstract
 */
class IsAbstractTest extends SelectorTestCase
{
    public function testMatchesAbstractClass(): void
    {
        $selector = new IsAbstract();
        $classReflection = $this->getReflectionClass(AbstractDummyClassValid::class);

        self::assertTrue($selector->matches($classReflection));
    }

    public function testDoesNotMatchNonAbstractClass(): void
    {
        $selector = new IsAbstract();
        $classReflection = $this->getReflectionClass(DummyClassInvalid::class);

        self::assertFalse($selector->matches($classReflection));
    }
}
