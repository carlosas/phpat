<?php

declare(strict_types=1);

namespace PhpAT\Statement;

use PhpAT\Rule\Type\RuleType;

/**
 * Class Statement
 *
 * @internal
 */
class Statement
{
    /**
     * @var RuleType
     */
    private $type;
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
        RuleType $type,
        bool $inverse,
        array $fqcnDestinations
    ) {
        $this->fqcnOrigin = $fqcnOrigin;
        $this->type = $type;
        $this->inverse = $inverse;
        $this->fqcnDestinations = $fqcnDestinations;
    }

    /**
     * @return RuleType
     */
    public function getType(): RuleType
    {
        return $this->type;
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
