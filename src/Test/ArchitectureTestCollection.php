<?php declare(strict_types=1);

namespace PhpAT\Test;

class ArchitectureTestCollection
{
    /** @var ArchitectureTest[] */
    private $values = [];

    /** @param ArchitectureTest[] $tests */
    public function __construct(array $tests = [])
    {
        foreach ($tests as $test) {
            $this->addValue($test);
        }
    }

    public function addValue(ArchitectureTest $test): void
    {
        $this->values[] = $test;
    }

    /** @return ArchitectureTest[] */
    public function getValues(): array
    {
        return $this->values;
    }
}
