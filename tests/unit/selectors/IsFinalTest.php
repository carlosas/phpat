<?php declare(strict_types=1);

namespace Tests\PHPat\unit\selectors;

use PHPat\Selector\IsFinal;

/**
 * @internal
 *
 * @covers \PHPat\Selector\IsFinal
 */
class IsFinalTest extends SelectorTestCase
{
    public function testMatchesFinalClass(): void
    {
        $selector = new IsFinal();
        $classReflection = $this->getReflectionClass(FinalDummyClassValid::class);

        self::assertTrue($selector->matches($classReflection));
    }

    public function testDoesNotMatchNonFinalClass(): void
    {
        $selector = new IsFinal();
        $classReflection = $this->getReflectionClass(DummyClassInvalid::class);

        self::assertFalse($selector->matches($classReflection));
    }
}
