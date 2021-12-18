<?php

declare(strict_types=1);

namespace PhpAT\Rule;

use PhpAT\Rule\Assertion\AbstractAssertion;
use PhpAT\Selector\SelectorInterface;

/**
 * Class Rule
 *
 * @package PhpAT\Rule
 */
class Rule
{
    private ?AbstractAssertion $assertion;
    private string $name = '';
    /** @var array<SelectorInterface> */
    private array $origin;
    /** @var array<SelectorInterface> */
    private array $originExcluded;
    /** @var array<SelectorInterface> */
    private array $destination;
    /** @var array<SelectorInterface> */
    private array $destinationExcluded;

    public function __construct(
        array $origin,
        array $originExcluded,
        ?AbstractAssertion $assertion,
        array $destination,
        array $destinationExcluded
    ) {
        $this->origin = $origin;
        $this->originExcluded = $originExcluded;
        $this->assertion = $assertion;
        $this->destination = $destination;
        $this->destinationExcluded = $destinationExcluded;
    }

    /**
     * @return array<SelectorInterface>
     */
    public function getOrigin(): array
    {
        return $this->origin;
    }

    /**
     * @return array<SelectorInterface>
     */
    public function getOriginExcluded(): array
    {
        return $this->originExcluded;
    }

    public function getAssertion(): ?AbstractAssertion
    {
        return $this->assertion;
    }

    /**
     * @return array<SelectorInterface>
     */
    public function getDestination(): array
    {
        return $this->destination;
    }

    /**
     * @return array<SelectorInterface>
     */
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
