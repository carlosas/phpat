<?php declare(strict_types=1);

namespace PHPArchiTest\Rule;

class RuleBuilder
{
    private $origin;
    private $destination;
    private $originExclude = [];
    private $destinationExclude = [];
    private $type;
    private $inverse;

    public function filesLike(string $origin): self
    {
        $this->origin = $origin;

        return $this;
    }

    public function withFilesLike(string $destination): self
    {
        $this->destination = $destination;

        return $this;
    }

    public function excluding(string $excluding): self
    {
        if (is_null($this->destination)) {
            $this->originExclude[] = $excluding;
        } else {
            $this->destinationExclude[] = $excluding;
        }
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
        $rule = new Rule($this->origin, $this->type, $this->destination, $this->inverse, '', $this->originExclude, $this->destinationExclude);
        $this->resetBuilder();

        return $rule;
    }

    private function resetBuilder(): void
    {
        $this->origin = null;
        $this->destination = null;
        $this->originExclude = [];
        $this->destinationExclude = [];
        $this->type = null;
        $this->inverse = null;
    }
}
