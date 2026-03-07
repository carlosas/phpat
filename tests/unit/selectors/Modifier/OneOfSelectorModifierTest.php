<?php declare(strict_types=1);

namespace Tests\PHPat\unit\selectors\Modifier;

use PHPat\Selector\Modifier\OneOfSelectorModifier;
use PHPat\Selector\SelectorInterface;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @covers \PHPat\Selector\Modifier\OneOfSelectorModifier
 */
class OneOfSelectorModifierTest extends TestCase
{
    public function testMatchesWhenExactlyOneMatches(): void
    {
        $classReflection = $this->createMock(\ReflectionClass::class);
        $s1 = $this->createMock(SelectorInterface::class);
        $s1->method('matches')->willReturn(true);
        $s2 = $this->createMock(SelectorInterface::class);
        $s2->method('matches')->willReturn(false);

        $modifier = new OneOfSelectorModifier($s1, $s2);
        self::assertTrue($modifier->matches($classReflection));
    }

    public function testDoesNotMatchWhenNoneMatch(): void
    {
        $classReflection = $this->createMock(\ReflectionClass::class);
        $s1 = $this->createMock(SelectorInterface::class);
        $s1->method('matches')->willReturn(false);
        $s2 = $this->createMock(SelectorInterface::class);
        $s2->method('matches')->willReturn(false);

        $modifier = new OneOfSelectorModifier($s1, $s2);
        self::assertFalse($modifier->matches($classReflection));
    }

    public function testDoesNotMatchWhenMultipleMatch(): void
    {
        $classReflection = $this->createMock(\ReflectionClass::class);
        $s1 = $this->createMock(SelectorInterface::class);
        $s1->method('matches')->willReturn(true);
        $s2 = $this->createMock(SelectorInterface::class);
        $s2->method('matches')->willReturn(true);

        $modifier = new OneOfSelectorModifier($s1, $s2);
        self::assertFalse($modifier->matches($classReflection));
    }
}
