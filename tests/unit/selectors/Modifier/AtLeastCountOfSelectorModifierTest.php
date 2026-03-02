<?php declare(strict_types=1);

namespace Tests\PHPat\unit\selectors\Modifier;

use PHPat\Selector\Modifier\AtLeastCountOfSelectorModifier;
use PHPat\Selector\SelectorInterface;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @covers \PHPat\Selector\Modifier\AtLeastCountOfSelectorModifier
 */
class AtLeastCountOfSelectorModifierTest extends TestCase
{
    public function testMatchesWhenCountMet(): void
    {
        $classReflection = $this->createMock(\ReflectionClass::class);
        $s1 = $this->createMock(SelectorInterface::class);
        $s1->method('matches')->willReturn(true);
        $s2 = $this->createMock(SelectorInterface::class);
        $s2->method('matches')->willReturn(true);
        $s3 = $this->createMock(SelectorInterface::class);
        $s3->method('matches')->willReturn(false);

        $modifier = new AtLeastCountOfSelectorModifier(2, $s1, $s2, $s3);
        self::assertTrue($modifier->matches($classReflection));
    }

    public function testDoesNotMatchWhenCountNotMet(): void
    {
        $classReflection = $this->createMock(\ReflectionClass::class);
        $s1 = $this->createMock(SelectorInterface::class);
        $s1->method('matches')->willReturn(true);
        $s2 = $this->createMock(SelectorInterface::class);
        $s2->method('matches')->willReturn(false);

        $modifier = new AtLeastCountOfSelectorModifier(2, $s1, $s2);
        self::assertFalse($modifier->matches($classReflection));
    }
}
