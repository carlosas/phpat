<?php

declare(strict_types=1);

namespace PhpAT\Statement;

use PhpAT\Rule\Type\RuleType;

/**
 * Class Statement
 * @internal
 */
class Statement
{
    private $parsedClass;
    private $type;
    private $inverse;
    private $destination;
    private $destinationExcluded;

    public function __construct(
        array $parsedClass,
        RuleType $type,
        bool $inverse,
        array $destination,
        array $destinationExcluded
    ) {
        $this->parsedClass = $parsedClass;
        $this->type = $type;
        $this->inverse = $inverse;
        $this->destination = $destination;
        $this->destinationExcluded = $destinationExcluded;
    }

    public function getParsedClass(): array
    {
        return $this->parsedClass;
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
}
