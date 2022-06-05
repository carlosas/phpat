<?php

declare(strict_types=1);

namespace PHPatOld\Rule;

use PHPatOld\Rule\Assertion\AbstractAssertion;
use PHPatOld\Selector\Selector;

/**
 * Class Rule
 *
 * @package PHPat\Rule
 */
class Rule
{
    private ?AbstractAssertion $assertion;
    private string $name = '';
    /** @var array<Selector> */
    private array $origin;
    /** @var array<Selector> */
    private array $originExcluded;
    /** @var array<Selector> */
    private array $destination;
    /** @var array<Selector> */
    private array $destinationExcluded;

    public function __construct(
        array $origin,
        array $originExcluded,
        ?AbstractAssertion $assertion,
        array $destination,
        array $destinationExcluded
    ) {
        $this->origin              = $origin;
        $this->originExcluded      = $originExcluded;
        $this->assertion           = $assertion;
        $this->destination         = $destination;
        $this->destinationExcluded = $destinationExcluded;
    }

    /**
     * @return array<Selector>
     */
    public function getOrigin(): array
    {
        return $this->origin;
    }

    /**
     * @return array<Selector>
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
     * @return array<Selector>
     */
    public function getDestination(): array
    {
        return $this->destination;
    }

    /**
     * @return array<Selector>
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
