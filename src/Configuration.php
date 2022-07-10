<?php

declare(strict_types=1);

namespace PHPat;

use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;

class Configuration
{
    private bool $ignore_doc_comments;
    /** @var array<ClassReflection> */
    private array $tests;
    private ReflectionProvider $reflectionProvider;

    /**
     * @param array<class-string> $tests
     */
    public function __construct(
        ReflectionProvider $reflectionProvider,
        bool $ignore_doc_comments,
        array $tests = []
    ) {
        $this->reflectionProvider  = $reflectionProvider;
        $this->ignore_doc_comments = $ignore_doc_comments;
        $this->tests               = $this->buildTests($tests);
    }

    /**
     * @return array<ClassReflection>
     */
    public function getTests(): array
    {
        return $this->tests;
    }

    public function ignoreDocComments(): bool
    {
        return $this->ignore_doc_comments;
    }

    /**
     * @param array<class-string> $tests
     * @return array<ClassReflection>
     */
    private function buildTests(array $tests): array
    {
        $return = [];
        foreach ($tests as $test) {
            if ($this->reflectionProvider->hasClass($test)) {
                $return[] = $this->reflectionProvider->getClass($test);
            }
        }

        return $return;
    }
}
