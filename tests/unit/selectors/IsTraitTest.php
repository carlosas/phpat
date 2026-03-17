<?php declare(strict_types=1);

namespace Tests\PHPat\unit\selectors;

use PHPat\Selector\IsTrait;

/**
 * @internal
 *
 * @covers \PHPat\Selector\IsTrait
 */
class IsTraitTest extends SelectorTestCase
{
    public function testMatchesTrait(): void
    {
        $selector = new IsTrait();
        $classReflection = $this->getReflectionClass(TraitDummyClassValid::class);

        self::assertTrue($selector->matches($classReflection));
    }

    public function testDoesNotMatchClass(): void
    {
        $selector = new IsTrait();
        $classReflection = $this->getReflectionClass(DummyClassInvalid::class);

        self::assertFalse($selector->matches($classReflection));
    }
}
