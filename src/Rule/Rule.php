<?php declare(strict_types=1);

namespace PhpAT\Rule;

use PhpAT\Rule\Type\RuleType;

class Rule
{
    private $source;
    private $type;
    private $inverse;
    private $name;
    private $excluded;
    private $params;

    public function __construct(
        string $source,
        RuleType $type,
        bool $inverse,
        array $params = [],
        string $name = '',
        array $excluded = []
    ) {
        $this->source = $source;
        $this->type = $type;
        $this->inverse = $inverse;
        $this->name = $name;
        $this->excluded = $excluded;
        $this->params = $params;
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function getType(): RuleType
    {
        return $this->type;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isInverse(): bool
    {
        return $this->inverse;
    }

    public function getExcluded(): array
    {
        return $this->excluded;
    }
}
