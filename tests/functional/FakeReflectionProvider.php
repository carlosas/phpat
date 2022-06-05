<?php

declare(strict_types=1);

namespace Tests\PHPat\functional;

use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider\DummyReflectionProvider;
use ReflectionClass;
use Throwable;

class FakeReflectionProvider extends DummyReflectionProvider
{
    public function hasClass(string $className): bool
    {
        try {
            (new ReflectionClass($className));
        } catch (Throwable $e) {
            return false;
        }

        return true;
    }

    public function getClass(string $className): ClassReflection
    {
        $reflection = (new ReflectionClass(ClassReflection::class));
        $property   = $reflection->getProperty('reflection');
        $property->setAccessible(true);

        /** @var ClassReflection $instance */
        $instance = $reflection->newInstanceWithoutConstructor();

        $property->setValue($instance, new ReflectionClass($className));

        return $instance;
    }
}
