<?php declare(strict_types=1);

namespace Tests\PHPat\unit\selectors;

use PHPat\Selector\IsStandardClass;
use PHPStan\Reflection\ClassReflection;
use PHPUnit\Framework\TestCase;
use Tests\PHPat\fixtures\Simple\SimpleClass;

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
        $classReflection = $this->createClassReflection($className);

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
        $classReflection = $this->createClassReflection($className);

        self::assertFalse($selector->matches($classReflection));
    }

    public static function getUserDefinedClassCases(): array
    {
        return [
            [SimpleClass::class],
            ['App\User'],
            ['MyCustomClass'],
            ['Vendor\Package\SomeClass'],
            ['Tests\PHPat\fixtures\Simple\SimpleClass'],
        ];
    }

    public function testHandlesNonExistentClass(): void
    {
        $selector = new IsStandardClass();
        $classReflection = $this->createClassReflection('NonExistentClass');

        self::assertFalse($selector->matches($classReflection));
    }

    public function testHandlesEmptyClassName(): void
    {
        $selector = new IsStandardClass();
        $classReflection = $this->createClassReflection('');

        self::assertFalse($selector->matches($classReflection));
    }

    public function testCaseSensitiveMatching(): void
    {
        $selector = new IsStandardClass();

        // Correct case should match
        $correctCaseReflection = $this->createClassReflection('stdClass');
        self::assertTrue($selector->matches($correctCaseReflection));

        // Incorrect case should not match
        $incorrectCaseReflection = $this->createClassReflection('stdclass');
        self::assertFalse($selector->matches($incorrectCaseReflection));

        $upperCaseReflection = $this->createClassReflection('STDCLASS');
        self::assertFalse($selector->matches($upperCaseReflection));
    }

    private function createClassReflection(string $className): ClassReflection
    {
        return new class($className) implements ClassReflection {
            private string $className;

            public function __construct(string $className)
            {
                $this->className = $className;
            }

            public function getName(): string
            {
                return $this->className;
            }

            // Implement other methods of ClassReflection as needed for the test
        };
    }
}
