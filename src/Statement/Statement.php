<?php

declare(strict_types=1);

namespace PhpAT\Statement;

use PhpAT\Parser\Ast\ClassLike;
use PhpAT\Rule\Assertion\AbstractAssertion;

/**
 * Class Statement
 *
 * @internal
 */
final class Statement
{
    private AbstractAssertion $assertion;
    private ClassLike $origin;
    /** @var array<ClassLike> */
    private array $destinations;
    /** @var array<ClassLike> */
    private array $excludedDestinations;

    public function __construct(
        ClassLike $origin,
        AbstractAssertion $assertion,
        array $destinations,
        array $excludedDestinations
    ) {
        $this->origin               = $origin;
        $this->assertion            = $assertion;
        $this->destinations         = $destinations;
        $this->excludedDestinations = $excludedDestinations;
    }

    public function getAssertion(): AbstractAssertion
    {
        return $this->assertion;
    }

    public function getOrigin(): ClassLike
    {
        return $this->origin;
    }

    /**
     * @return array<ClassLike>
     */
    public function getDestinations(): array
    {
        return $this->destinations;
    }

    /**
     * @return array<ClassLike>
     */
    public function getExcludedDestinations(): array
    {
        return $this->excludedDestinations;
    }
}
