<?php declare(strict_types=1);

namespace Tests\PHPat\unit\selectors;

use PHPat\Selector\IsNotFinal;

/**
 * @internal
 *
 * @covers \PHPat\Selector\IsNotFinal
 */
class IsNotFinalTest extends SelectorTestCase
{
    public function testMatchesNotFinal(): void
    {
        $selector = new IsNotFinal();
        $classReflection = $this->getReflectionClass(DummyClassValid::class);

        self::assertTrue($selector->matches($classReflection));
    }

    public function testDoesNotMatchFinal(): void
    {
        $selector = new IsNotFinal();
        $classReflection = $this->getReflectionClass(FinalDummyClassValid::class);

        self::assertFalse($selector->matches($classReflection));
    }
}
