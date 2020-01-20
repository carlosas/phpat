<?php

declare(strict_types=1);

namespace PhpAT\Statement;

use PhpAT\Rule\Assertion\Assertion;

/**
 * Class Statement
 *
 * @internal
 */
class Statement
{
    /**
     * @var Assertion
     */
    private $assertion;
    /**
     * @var bool
     */
    private $inverse;
    /**
     * @var string
     */
    private $fqcnOrigin;
    /**
     * @var string
     */
    private $fqcnDestinations;

    public function __construct(
        string $fqcnOrigin,
        Assertion $assertion,
        bool $inverse,
        array $fqcnDestinations
    ) {
        $this->fqcnOrigin = $fqcnOrigin;
        $this->assertion = $assertion;
        $this->inverse = $inverse;
        $this->fqcnDestinations = $fqcnDestinations;
    }

    /**
     * @return Assertion
     */
    public function getAssertion(): Assertion
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
     * @return string
     */
    public function getFqcnOrigin(): string
    {
        return $this->fqcnOrigin;
    }

    /**
     * @return string[]
     */
    public function getFqcnDestinations(): array
    {
        return $this->fqcnDestinations;
    }
}
