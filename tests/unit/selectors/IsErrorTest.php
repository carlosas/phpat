<?php declare(strict_types=1);

namespace Tests\PHPat\unit\selectors;

use PHPat\Selector\IsError;

/**
 * @internal
 *
 * @covers \PHPat\Selector\IsError
 */
class IsErrorTest extends SelectorTestCase
{
    public function testMatchesErrorSubclass(): void
    {
        $selector = new IsError();
        $classReflection = $this->getReflectionClass(ErrorDummyClassValid::class);

        self::assertTrue($selector->matches($classReflection));
    }

    public function testDoesNotMatchDifferentClass(): void
    {
        $selector = new IsError();
        $classReflection = $this->getReflectionClass(DummyClassInvalid::class);

        self::assertFalse($selector->matches($classReflection));
    }
}
