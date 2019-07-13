<?php

declare(strict_types=1);

namespace PHPArchiTest\Test;

class ArchiTestCollection
{
    private $values = [];

    /**
     * ArchiTestCollection constructor.
     * @param ArchiTest[] $tests
     */
    public function __construct(array $tests = [])
    {
        foreach ($tests as $test) {
            $this->addValue($test);
        }
    }

    public function addValue(ArchiTest $test): void
    {
        $this->values[] = $test;
    }

    /**
     * @return ArchiTest[]
     */
    public function getValues(): array
    {
        return $this->values;
    }
}
