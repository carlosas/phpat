<?php

declare(strict_types=1);

namespace PhpAT\Test;

class ArchitectureTestCollection
{
    private $values = [];

    /**
     * ArchitectureTestCollection constructor.
     *
     * @param TestInterface[] $tests
     */
    public function __construct(array $tests = [])
    {
        foreach ($tests as $test) {
            $this->addValue($test);
        }
    }

    public function addValue(TestInterface $test): void
    {
        $this->values[] = $test;
    }

    /**
     * @return TestInterface[]
     */
    public function getValues(): array
    {
        return $this->values;
    }
}
