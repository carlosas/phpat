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
    /**
     * @var SelectorInterface[]
     */
    private $origin;
    /**
     * @var SelectorInterface[]
     */
    private $originExcluded;
    /**
     * @var AbstractAssertion|null
     */
    private $assertion;
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
     * @return AbstractAssertion|null
     */
    public function getAssertion(): ?AbstractAssertion
    {
        return $this->assertion;
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
