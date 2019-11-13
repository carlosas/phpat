<?php declare(strict_types=1);

namespace PhpAT\Statement;

use PhpAT\Rule\Type\RuleType;

/** @internal */
class Statement
{
    /**
     * TODO What's the type of the elements here?
     * @var array
     */
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
    )
    {
        $this->parsedClass = $parsedClass;
        $this->type = $type;
        $this->inverse = $inverse;
        $this->destinations = $destinations;
    }

    public function getParsedClass(): array
    {
        return $this->parsedClass;
    }

    /** @return RuleType */
    public function getType(): RuleType
    {
        return $this->type;
    }

    public function isInverse(): bool
    {
        return $this->inverse;
    }

    /** @return \SplFileInfo[] */
    public function getDestinations(): array
    {
        return $this->destinations;
    }
}
