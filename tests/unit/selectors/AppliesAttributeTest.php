<?php

namespace Tests\PHPat\unit\selectors;

use PHPat\Selector\AppliesAttribute;
use PHPStan\Reflection\ClassReflection;
use PHPUnit\Framework\TestCase;
use Tests\PHPat\fixtures\FixtureClass;
use Tests\PHPat\fixtures\Simple\SimpleAttribute;

/**
 * @internal
 *
 * @covers \PHPat\Selector\AppliesAttribute
 */
class AppliesAttributeTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (PHP_VERSION_ID < 80000) {
            self::markTestSkipped();
        }
    }

    public function testGetName(): void
    {
        $selector = new AppliesAttribute('test');

        $this->assertEquals('test', $selector->getName());
    }

    /**
     * @dataProvider getCases
     */
    public function testMatches(string $name, array $arguments, bool $isRegex, bool $expected): void
    {
        $selector = new AppliesAttribute($name, $isRegex, $arguments);

        self::assertSame($expected, $selector->matches($this->getClassReflection()));
    }

    public function getCases(): array
    {
        return [
            [SimpleAttribute::class, [], false, true],
            ['Fake\Attribute', [], false, false],
            ['/\bSimple[A-Z][a-zA-Z0-9_]*\b/', [], true, true],
            ['/\bFake[A-Z][a-zA-Z0-9_]*\b/', [], true, false],
            [SimpleAttribute::class, ['something' => 'something'], false, true],
            [SimpleAttribute::class, ['something' => 'somethingElse'], false, false],
        ];
    }

    private function getClassReflection(): ClassReflection
    {
        $ref = new \ReflectionClass(ClassReflection::class);
        $instance = $ref->newInstanceWithoutConstructor();

        $reflectionProperty = $ref->getProperty('reflection');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($instance, new \ReflectionClass(FixtureClass::class));

        return $instance;
    }
}
