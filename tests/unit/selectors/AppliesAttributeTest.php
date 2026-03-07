<?php declare(strict_types=1);

namespace Tests\PHPat\unit\selectors;

use PHPat\Selector\AppliesAttribute;

/**
 * @internal
 *
 * @covers \PHPat\Selector\AppliesAttribute
 */
class AppliesAttributeTest extends SelectorTestCase
{
    public function testMatchesAttribute(): void
    {
        $selector = new AppliesAttribute(MyAttribute::class, false);
        $classReflection = $this->getReflectionClass(AttributeDummyClassValid::class);

        self::assertTrue($selector->matches($classReflection));
    }

    public function testDoesNotMatchDifferentAttribute(): void
    {
        $selector = new AppliesAttribute('App\MyAttribute', false);
        $classReflection = $this->getReflectionClass(AttributeDummyClassValid::class);

        self::assertFalse($selector->matches($classReflection));
    }

    public function testMatchesRegex(): void
    {
        $selector = new AppliesAttribute('/^Tests\\\PHPat\\\unit\\\selectors\\\MyAttribute/', true);
        $classReflection = $this->getReflectionClass(AttributeDummyClassValid::class);

        self::assertTrue($selector->matches($classReflection));
    }
}
