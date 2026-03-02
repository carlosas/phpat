<?php declare(strict_types=1);

namespace Tests\PHPat\unit\selectors;

use PHPat\Selector\IsStandardClass;

/**
 * @internal
 *
 * @covers \PHPat\Selector\IsStandardClass
 */
class IsStandardClassTest extends SelectorTestCase
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
        $classReflection = $this->getReflectionClass($className);

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
        // For testing "doesn't match", the actual name here doesn't matter inside the dummy class,
        // it just needs to be a valid ClassReflection object of a non-standard class.
        $selector = new IsStandardClass();
        $classReflection = $this->getReflectionClass(DummyClassInvalid::class);

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
