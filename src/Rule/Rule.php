<?php

declare(strict_types=1);

namespace PhpAT\Rule;

use PhpAT\Rule\Type\RuleType;

class Rule
{
    private $origin;
    private $originExcluded;
    private $type;
    private $inverse;
    private $destination;
    private $destinationExcluded;
    private $name;

    public function __construct(
        array $origin,
        array $originExcluded,
        RuleType $type,
        bool $inverse,
        array $destination,
        array $destinationExcluded
    ) {
        $this->origin = $origin;
        $this->originExcluded = $originExcluded;
        $this->type = $type;
        $this->inverse = $inverse;
        $this->destination = $destination;
        $this->destinationExcluded = $destinationExcluded;
    }

    public function getOrigin(): array
    {
        return $this->origin;
    }

    public function getOriginExcluded(): array
    {
        return $this->originExcluded;
    }

    public function getType(): RuleType
    {
        return $this->type;
    }

    public function isInverse(): bool
    {
        return $this->inverse;
    }

    public function getDestination(): array
    {
        return $this->destination;
    }

    public function getDestinationExcluded(): array
    {
        return $this->destinationExcluded;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
