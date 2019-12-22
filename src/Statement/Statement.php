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
    private $fqcnDestination;

    public function __construct(
        string $fqcnOrigin,
        RuleType $type,
        bool $inverse,
        string $fqcnDestination
    ) {
        $this->fqcnOrigin = $fqcnOrigin;
        $this->type = $type;
        $this->inverse = $inverse;
        $this->fqcnDestination = $fqcnDestination;
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
     * @return string
     */
    public function getFqcnDestination(): string
    {
        return $this->fqcnDestination;
    }
}
