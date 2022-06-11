<?php

declare(strict_types=1);

namespace PHPat\Test;

use ReflectionMethod;

class TestParser
{
    /** @var array<Rule> */
    private static array $result = [];
    private TestExtractor $extractor;

    public function __construct(TestExtractor $extractor)
    {
        $this->extractor = $extractor;
    }

    /**
     * @return array<Rule>
     */
    public function __invoke(): array
    {
        if (empty(self::$result)) {
            self::$result = $this->parse();
        }

        return self::$result;
    }

    /**
     * @return array<Rule>
     */
    private function parse(): array
    {
        $tests = ($this->extractor)();

        $rules = [];
        foreach ($tests as $test) {
            $methods   = [];
            $reflected = $test->getNativeReflection();
            foreach ($reflected->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
                if (preg_match('/^(test)[A-Za-z0-9_\x80-\xff]*/', $method->getName())) {
                    $methods[] = $method->getName();
                }
            }

            $object = $reflected->newInstanceWithoutConstructor();
            foreach ($methods as $method) {
                $rules[] = $object->{$method}();
            }
        }

        return $rules;
    }
}
