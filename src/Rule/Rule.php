<?php

declare(strict_types=1);

namespace PhpAT\Rule;

use PhpAT\Rule\Assertion\Assertion;
use PhpAT\Selector\SelectorInterface;

/**
 * Class Rule
 *
 * @package PhpAT\Rule
 */
class Rule
{
    /**
     * @var SelectorInterface[]
     */
    private $origin;
    /**
     * @var SelectorInterface[]
     */
    private $originExcluded;
    /**
     * @var Assertion|null
     */
    private $assertion;
    /**
     * @var bool
     */
    private $inverse;
    /**
     * @var SelectorInterface[]
     */
    private $destination;
    /**
     * @var SelectorInterface[]
     */
    private $destinationExcluded;
    /**
     * @var string
     */
    private $name;

    public function __construct(
        array $origin,
        array $originExcluded,
        ?Assertion $assertion,
        bool $inverse,
        array $destination,
        array $destinationExcluded
    ) {
        $this->origin = $origin;
        $this->originExcluded = $originExcluded;
        $this->assertion = $assertion;
        $this->inverse = $inverse;
        $this->destination = $destination;
        $this->destinationExcluded = $destinationExcluded;
    }

    /**
     * @return SelectorInterface[]
     */
    public function getOrigin(): array
    {
        return $this->origin;
    }

    /**
     * @return SelectorInterface[]
     */
    public function getOriginExcluded(): array
    {
        return $this->originExcluded;
    }

    /**
     * @return Assertion|null
     */
    public function getAssertion(): ?Assertion
    {
        return $this->assertion;
    }

    /**
     * @return bool
     */
    public function isInverse(): bool
    {
        return $this->inverse;
    }

    /**
     * @return SelectorInterface[]
     */
    public function getDestination(): array
    {
        return $this->destination;
    }

    /**
     * @return SelectorInterface[]
     */
    public function getDestinationExcluded(): array
    {
        return $this->destinationExcluded;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
