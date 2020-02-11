<?php

declare(strict_types=1);

namespace PhpAT\Statement;

use PhpAT\Parser\ClassLike;
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
     * @var ClassLike
     */
    private $origin;
    /**
     * @var ClassLike[]
     */
    private $destinations;

    public function __construct(
        ClassLike $origin,
        Assertion $assertion,
        array $destinations
    ) {
        $this->origin = $origin;
        $this->assertion = $assertion;
        $this->destinations = $destinations;
    }

    /**
     * @return Assertion
     */
    public function getAssertion(): Assertion
    {
        return $this->assertion;
    }

    /**
     * @return ClassLike
     */
    public function getOrigin(): ClassLike
    {
        return $this->origin;
    }

    /**
     * @return ClassLike[]
     */
    public function getDestinations(): array
    {
        return $this->destinations;
    }
}
