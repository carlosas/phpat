<?php declare(strict_types=1);

namespace Tests\PHPat\unit\selectors;

use PHPat\Selector\IsException;

/**
 * @internal
 *
 * @covers \PHPat\Selector\IsException
 */
class IsExceptionTest extends SelectorTestCase
{
    public function testMatchesExceptionSubclass(): void
    {
        $selector = new IsException();
        $classReflection = $this->getReflectionClass(ExceptionDummyClassValid::class);

        self::assertTrue($selector->matches($classReflection));
    }

    public function testDoesNotMatchDifferentClass(): void
    {
        $selector = new IsException();
        $classReflection = $this->getReflectionClass(DummyClassInvalid::class);

        self::assertFalse($selector->matches($classReflection));
    }
}
