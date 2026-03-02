<?php declare(strict_types=1);

namespace Tests\PHPat\unit\selectors\Modifier;

use PHPat\Selector\Modifier\NoneOfSelectorModifier;
use PHPat\Selector\SelectorInterface;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @covers \PHPat\Selector\Modifier\NoneOfSelectorModifier
 */
class NoneOfSelectorModifierTest extends TestCase
{
    public function testMatchesWhenNoneMatch(): void
    {
        $classReflection = $this->createMock(\ReflectionClass::class);
        $s1 = $this->createMock(SelectorInterface::class);
        $s1->method('matches')->willReturn(false);
        $s2 = $this->createMock(SelectorInterface::class);
        $s2->method('matches')->willReturn(false);

        $modifier = new NoneOfSelectorModifier($s1, $s2);
        self::assertTrue($modifier->matches($classReflection));
    }

    public function testDoesNotMatchWhenOneMatches(): void
    {
        $classReflection = $this->createMock(\ReflectionClass::class);
        $s1 = $this->createMock(SelectorInterface::class);
        $s1->method('matches')->willReturn(true);
        $s2 = $this->createMock(SelectorInterface::class);
        $s2->method('matches')->willReturn(false);

        $modifier = new NoneOfSelectorModifier($s1, $s2);
        self::assertFalse($modifier->matches($classReflection));
    }
}
