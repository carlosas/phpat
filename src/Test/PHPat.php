<?php

declare(strict_types=1);

namespace PHPat\Test;

use PHPat\Rule\Assertion\ShouldNotConstruct\ShouldNotConstruct;
use PHPat\Rule\Assertion\ShouldNotDepend\ShouldNotDepend;
use PHPat\Rule\Assertion\ShouldNotExtend\ShouldNotExtend;
use PHPat\Rule\Assertion\ShouldNotImplement\ShouldNotImplement;
use PHPat\Selector\SelectorInterface;
use PHPStan\Rules\Rule as PHPSanRule;

class PHPat
{
    /** @var array<SelectorInterface> */
    private array $subjects = [];
    /** @var array<SelectorInterface> */
    private array $targets = [];
    /** @var null|class-string<PHPSanRule> */
    private ?string $assertion = null;

    public static function rule(): self
    {
        return new PHPat();
    }

    public function classes(SelectorInterface ...$selectors): self
    {
        foreach ($selectors as $selector) {
            if ($this->assertion === null) {
                $this->subjects[] = $selector;
            } else {
                $this->targets[] = $selector;
            }
        }

        return $this;
    }

    public function shouldNotDependOn(): self
    {
        $this->assertion = ShouldNotDepend::class;

        return $this;
    }

    public function shouldNotConstruct(): self
    {
        $this->assertion = ShouldNotConstruct::class;

        return $this;
    }

    public function shouldNotExtend(): self
    {
        $this->assertion = ShouldNotExtend::class;

        return $this;
    }

    public function shouldNotImplement(): self
    {
        $this->assertion = ShouldNotImplement::class;

        return $this;
    }

    public function build(): Rule
    {
        if ($this->assertion === null) {
            throw new \RuntimeException('No assertion specified');
        }

        $rule = new Rule($this->subjects, $this->targets, $this->assertion);
        $this->reset();

        return $rule;
    }

    private function reset(): void
    {
        $this->subjects = [];
        $this->targets = [];
        $this->assertion = null;
    }
}
