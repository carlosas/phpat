<?php declare(strict_types=1);

namespace Tests\PHPat\unit\selectors\Modifier;

use PHPat\Selector\Modifier\AllOfSelectorModifier;
use PHPat\Selector\SelectorInterface;
use Tests\PHPat\unit\selectors\SelectorTestCase;

/**
 * @internal
 *
 * @covers \PHPat\Selector\Modifier\AllOfSelectorModifier
 */
class AllOfSelectorModifierTest extends SelectorTestCase
{
    public function testMatchesWhenAllMatch(): void
    {
        $classReflection = $this->getReflectionClass(\stdClass::class);
        $s1 = $this->createMock(SelectorInterface::class);
        $s1->method('matches')->willReturn(true);
        $s2 = $this->createMock(SelectorInterface::class);
        $s2->method('matches')->willReturn(true);

        $modifier = new AllOfSelectorModifier($s1, $s2);
        self::assertTrue($modifier->matches($classReflection));
    }

    public function testDoesNotMatchWhenOneFails(): void
    {
        $classReflection = $this->getReflectionClass(\stdClass::class);
        $s1 = $this->createMock(SelectorInterface::class);
        $s1->method('matches')->willReturn(true);
        $s2 = $this->createMock(SelectorInterface::class);
        $s2->method('matches')->willReturn(false);

        $modifier = new AllOfSelectorModifier($s1, $s2);
        self::assertFalse($modifier->matches($classReflection));
    }
}
