<?php

declare(strict_types=1);

namespace PhpAT\Rule;

class RuleCollection
{
    private array $values = [];

    /**
     * @param array<Rule> $rules
     */
    public function __construct(array $rules = [])
    {
        foreach ($rules as $rule) {
            $this->addValue($rule);
        }
    }

    public function addValue(Rule $rule): void
    {
        $this->values[] = $rule;
    }

    /**
     * @return array<Rule>
     */
    public function getValues(): array
    {
        return $this->values;
    }

    public function merge(RuleCollection $collection): RuleCollection
    {
        return new RuleCollection(array_merge($this->getValues(), $collection->getValues()));
    }
}
