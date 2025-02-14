<?php

namespace Tests\PHPat\unit\selectors;

use PHPat\Selector\AppliesAttribute;
use PHPStan\Reflection\ClassReflection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;
use Tests\PHPat\fixtures\FixtureClass;
use Tests\PHPat\fixtures\Simple\SimpleAttribute;

/**
 * @internal
 */
#[CoversClass(AppliesAttribute::class)]
class AppliesAttributeTest extends TestCase
{
    public function testGetName()
    {
        $selector = new AppliesAttribute('test');

        $this->assertEquals('test', $selector->getName());
    }

    #[TestWith(['name' => SimpleAttribute::class, [], false, true])]
    #[TestWith(['name' => 'Fake\Attribute', [], false, false])]
    #[TestWith(['name' => '/\bSimple[A-Z][a-zA-Z0-9_]*\b/', [], true, true])]
    #[TestWith(['name' => '/\bFake[A-Z][a-zA-Z0-9_]*\b/', [], true, false])]
    #[TestWith(['name' => SimpleAttribute::class, ['something' => 'something'], false, true])]
    #[TestWith(['name' => SimpleAttribute::class, ['something' => 'somethingElse'], false, false])]
    public function testMatches(string $name, array $arguments, bool $isRegex, bool $expected)
    {
        if (PHP_VERSION_ID < 80000) {
            self::markTestSkipped();
        }

        $selector = new AppliesAttribute($name, $isRegex, $arguments);

        self::assertSame($expected, $selector->matches($this->getClassReflection()));
    }

    private function getClassReflection(): ClassReflection
    {
        $ref = new \ReflectionClass(ClassReflection::class);
        $instance = $ref->newInstanceWithoutConstructor();
        $ref->getProperty('reflection')->setValue($instance, new \ReflectionClass(FixtureClass::class));

        return $instance;
    }
}
