<?php declare(strict_types=1);

namespace Tests\PHPat\unit\selectors\Modifier;

use PHPat\Selector\Modifier\AtMostCountOfSelectorModifier;
use PHPat\Selector\SelectorInterface;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @covers \PHPat\Selector\Modifier\AtMostCountOfSelectorModifier
 */
class AtMostCountOfSelectorModifierTest extends TestCase
{
    public function testMatchesWhenCountBelowLimit(): void
    {
        $classReflection = $this->createMock(\ReflectionClass::class);
        $s1 = $this->createMock(SelectorInterface::class);
        $s1->method('matches')->willReturn(true);
        $s2 = $this->createMock(SelectorInterface::class);
        $s2->method('matches')->willReturn(false);

        $modifier = new AtMostCountOfSelectorModifier(1, $s1, $s2);
        self::assertTrue($modifier->matches($classReflection));
    }

    public function testDoesNotMatchWhenCountExceedsLimit(): void
    {
        $classReflection = $this->createMock(\ReflectionClass::class);
        $s1 = $this->createMock(SelectorInterface::class);
        $s1->method('matches')->willReturn(true);
        $s2 = $this->createMock(SelectorInterface::class);
        $s2->method('matches')->willReturn(true);

        $modifier = new AtMostCountOfSelectorModifier(1, $s1, $s2);
        self::assertFalse($modifier->matches($classReflection));
    }
}
