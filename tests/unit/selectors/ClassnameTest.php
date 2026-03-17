<?php declare(strict_types=1);

namespace Tests\PHPat\unit\selectors;

use PHPat\Selector\Classname;

/**
 * @internal
 *
 * @covers \PHPat\Selector\Classname
 */
class ClassnameTest extends SelectorTestCase
{
    public function testGetName(): void
    {
        $selector = new Classname(DummyClassValid::class, false);

        $this->assertEquals(DummyClassValid::class, $selector->getName());
    }

    public function testMatchesExactName(): void
    {
        $selector = new Classname(DummyClassValid::class, false);
        $classReflection = $this->getReflectionClass(DummyClassValid::class);

        self::assertTrue($selector->matches($classReflection));
    }

    public function testMatchesExactNameWithLeadingBackslash(): void
    {
        $selector = new Classname('\\'.DummyClassValid::class, false);
        $classReflection = $this->getReflectionClass(DummyClassValid::class);

        self::assertTrue($selector->matches($classReflection));
    }

    public function testDoesNotMatchDifferentName(): void
    {
        $selector = new Classname(DummyClassValid::class, false);
        $classReflection = $this->getReflectionClass(DummyClassInvalid::class);

        self::assertFalse($selector->matches($classReflection));
    }

    public function testMatchesRegex(): void
    {
        $selector = new Classname('/^Tests\\\PHPat\\\unit\\\selectors\\\DummyClass/', true);
        $classReflection = $this->getReflectionClass(DummyClassValid::class);

        self::assertTrue($selector->matches($classReflection));
    }

    public function testDoesNotMatchRegex(): void
    {
        $selector = new Classname('/^Vendor\\\/', true);
        $classReflection = $this->getReflectionClass(DummyClassValid::class);

        self::assertFalse($selector->matches($classReflection));
    }
}
