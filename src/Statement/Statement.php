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
    /** @var array */
    private $parsedClass;
    /** @var RuleType */
    private $type;
    /** @var bool */
    private $inverse;
    /** @var \SplFileInfo[] */
    private $destinations;

    public function __construct(
        array $parsedClass,
        RuleType $type,
        bool $inverse,
        array $destinations
    ) {
        $this->parsedClass = $parsedClass;
        $this->type = $type;
        $this->inverse = $inverse;
        $this->destinations = $destinations;
    }

    /**
     * @return array
     */
    public function getParsedClass(): array
    {
        return $this->parsedClass;
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
     * @return \SplFileInfo[]
     */
    public function getDestinations(): array
    {
        return $this->destinations;
    }
}
