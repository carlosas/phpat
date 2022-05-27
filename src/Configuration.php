<?php

declare(strict_types=1);

namespace PHPat;

use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;

class Configuration
{
    /** @var array<ClassReflection> */
    public array $tests;
    private ReflectionProvider $reflectionProvider;

    /**
     * @param array<class-string> $tests
     */
    public function __construct(
        ReflectionProvider $reflectionProvider,
        array $tests = []
    ) {
        $this->reflectionProvider = $reflectionProvider;
        $this->tests              = $this->buildTests($tests);
    }

    private function buildTests(array $tests): array
    {
        foreach ($tests as $test) {
            if ($this->reflectionProvider->hasClass($test)) {
                $return[] = $this->reflectionProvider->getClass($test);
            }
        }

        return $return ?? [];
    }
}
