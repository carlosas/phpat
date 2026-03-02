<?php declare(strict_types=1);

namespace Tests\PHPat\unit\selectors;

use PHPat\Selector\IsAttribute;

/**
 * @internal
 *
 * @covers \PHPat\Selector\IsAttribute
 */
class IsAttributeTest extends SelectorTestCase
{
    public function testMatchesAttribute(): void
    {
        $selector = new IsAttribute();
        $classReflection = $this->getReflectionClass(MyAttribute::class);

        self::assertTrue($selector->matches($classReflection));
    }

    public function testDoesNotMatchClass(): void
    {
        $selector = new IsAttribute();
        $classReflection = $this->getReflectionClass(DummyClassInvalid::class);

        self::assertFalse($selector->matches($classReflection));
    }
}
