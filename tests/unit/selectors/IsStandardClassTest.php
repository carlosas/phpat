<?php declare(strict_types=1);

namespace Tests\PHPat\unit\selectors;

use PHPat\Selector\IsStandardClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @covers \PHPat\Selector\IsStandardClass
 */
class IsStandardClassTest extends TestCase
{
    public function testGetName(): void
    {
        $selector = new IsStandardClass();

        $this->assertEquals('-standard classes-', $selector->getName());
    }

    /**
     * @dataProvider getBuiltInClassCases
     */
    public function testMatchesBuiltInClasses(string $className): void
    {
        $selector = new IsStandardClass();
        $classReflection = $this->createMock(\ReflectionClass::class);
        $classReflection->method('getName')->willReturn($className);

        self::assertTrue($selector->matches($classReflection));
    }

    public static function getBuiltInClassCases(): array
    {
        return [
            ['stdClass'],
            ['Exception'],
            ['Iterator'],
            ['Throwable'],
            ['Generator'],
            ['Countable'],
            ['ArrayAccess'],
            ['Closure'],
            ['Error'],
            ['TypeError'],
            ['ValueError'],
        ];
    }

    /**
     * @dataProvider getUserDefinedClassCases
     */
    public function testDoesNotMatchUserDefinedClasses(string $className): void
    {
        $selector = new IsStandardClass();
        $classReflection = $this->createMock(\ReflectionClass::class);
        $classReflection->method('getName')->willReturn($className);

        self::assertFalse($selector->matches($classReflection));
    }

    public static function getUserDefinedClassCases(): array
    {
        return [
            ['App\User'],
            ['MyCustomClass'],
            ['Vendor\Package\SomeClass'],
        ];
    }
}
