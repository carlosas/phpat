<?php
declare(strict_types=1);

namespace PHPArchiTest\Rule;

class RuleBuilder
{
    private $origin;
    private $type;
    private $destination;
    private $inverse;

    public function class(string $origin): self
    {
        $this->origin = $origin;

        return $this;
    }

    public function withClass(string $destination): self
    {
        $this->destination = $destination;

        return $this;
    }

    public function shouldHave(RuleType $type): self
    {
        $this->type = $type;
        $this->inverse = false;

        return $this;
    }

    public function shouldNotHave(RuleType $type): self
    {
        $this->type = $type;
        $this->inverse = true;

        return $this;
    }

    public function build(): Rule
    {
        $rule = new Rule($this->origin, $this->type, $this->destination, $this->inverse);
        $this->resetBuilder();

        return $rule;
    }

    private function resetBuilder(): void
    {
        $this->origin = null;
        $this->type = null;
        $this->destination = null;
        $this->inverse = null;
    }
}
