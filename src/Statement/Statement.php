<?php declare(strict_types=1);

namespace PhpAT\Statement;

use PhpAT\Rule\RuleType;

/**
 * Class Statement
 * @package PhpAT\Rule
 * @internal
 */
class Statement
{
    private $origin;
    private $type;
    private $params;
    private $inverse;
    private $errorMessage;

    public function __construct(array $origin, RuleType $type, array $params, bool $inverse, string $errorMessage)
    {
        $this->origin = $origin;
        $this->type = $type;
        $this->params = $params;
        $this->inverse = $inverse;
        $this->errorMessage = $errorMessage;
    }

    public function getOrigin(): array
    {
        return $this->origin;
    }

    public function getType(): RuleType
    {
        return $this->type;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function isInverse(): bool
    {
        return $this->inverse;
    }

    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }
}
