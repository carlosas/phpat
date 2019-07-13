<?php
declare(strict_types=1);

namespace PHPArchiTest\Statement;

use PHPArchiTest\Rule\RuleType;
use Roave\BetterReflection\Reflection\ReflectionClass;

/**
 * Class Statement
 * @package PHPArchiTest\Rule
 * @internal
 */
class Statement
{
    private $origin;
    private $type;
    private $destination;
    private $inverse;
    private $name;

    public function __construct(ReflectionClass $origin, RuleType $type, ReflectionClass $destination, bool $inverse, string $name)
    {
        $this->origin = $origin;
        $this->type = $type;
        $this->destination = $destination;
        $this->inverse = $inverse;
        $this->name = $name;
    }

    public function getOrigin(): ReflectionClass
    {
        return $this->origin;
    }

    public function getType(): RuleType
    {
        return $this->type;
    }

    public function getDestination(): ReflectionClass
    {
        return $this->destination;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isInverse(): bool
    {
        return $this->inverse;
    }
}
