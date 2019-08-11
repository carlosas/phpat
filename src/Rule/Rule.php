<?php declare(strict_types=1);

namespace PHPArchiTest\Rule;

class Rule
{
    private $origin;
    private $type;
    private $destination;
    private $inverse;
    private $name;
    private $originExcluded;
    private $destinationExcluded;

    public function __construct(
        string $origin,
        RuleType $type,
        string $destination,
        bool $inverse,
        string $name = '',
        array $originExcluded = [],
        array $destinationExcluded = []
    ) {
        $this->origin = $origin;
        $this->type = $type;
        $this->destination = $destination;
        $this->inverse = $inverse;
        $this->name = $name;
        $this->originExcluded = $originExcluded;
        $this->destinationExcluded = $destinationExcluded;
    }

    public function getOrigin(): string
    {
        return $this->origin;
    }

    public function getType(): RuleType
    {
        return $this->type;
    }

    public function getDestination(): string
    {
        return $this->destination;
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

    public function getOriginExcluded(): array
    {
        return $this->originExcluded;
    }

    public function getDestinationExcluded(): array
    {
        return $this->destinationExcluded;
    }
}
