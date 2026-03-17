<?php declare(strict_types=1);

namespace Tests\PHPat\unit\selectors;

use PHPat\Selector\Classname;
use PHPat\Selector\ClassNamespace;
use PHPat\Selector\Modifier\AndModifier;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @covers \PHPat\Selector\ClassNamespace
 */
class ClassNamespaceTest extends SelectorTestCase
{
    public function testGetName(): void
    {
        $selector = new ClassNamespace('PHPat\Selector', false);

        $this->assertEquals('PHPat\Selector', $selector->getName());
    }

    public function testMatchesNamespace(): void
    {
        $selector = new ClassNamespace('PHPat', false);
        $classReflection = $this->getReflectionClass(Classname::class);

        self::assertTrue($selector->matches($classReflection));
    }

    public function testMatchesSubNamespace(): void
    {
        $selector = new ClassNamespace('PHPat', false);
        $classReflection = $this->getReflectionClass(AndModifier::class);

        self::assertTrue($selector->matches($classReflection));
    }

    public function testDoesNotMatchDifferentNamespace(): void
    {
        $selector = new ClassNamespace('PHPat', false);
        $classReflection = $this->getReflectionClass(TestCase::class);

        self::assertFalse($selector->matches($classReflection));
    }

    public function testMatchesRegex(): void
    {
        $selector = new ClassNamespace('/^PHPat\\\Selector/', true);
        $classReflection = $this->getReflectionClass(AndModifier::class);

        self::assertTrue($selector->matches($classReflection));
    }
}
