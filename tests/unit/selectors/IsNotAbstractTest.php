<?php declare(strict_types=1);

namespace Tests\PHPat\unit\selectors;

use PHPat\Selector\IsNotAbstract;

/**
 * @internal
 *
 * @covers \PHPat\Selector\IsNotAbstract
 */
class IsNotAbstractTest extends SelectorTestCase
{
    public function testMatchesConcreteClass(): void
    {
        $selector = new IsNotAbstract();
        $classReflection = $this->getReflectionClass(DummyClassValid::class);

        self::assertTrue($selector->matches($classReflection));
    }

    public function testDoesNotMatchAbstract(): void
    {
        $selector = new IsNotAbstract();
        $classReflection = $this->getReflectionClass(AbstractDummyClassValid::class);

        self::assertFalse($selector->matches($classReflection));
    }
}
