<?php

declare(strict_types=1);

namespace PhpAT\Test;

use PhpAT\Rule\Assertion\Dependency\MustNotDepend\MustNotDepend;
use PhpAT\Selector\SelectorInterface;
use PHPStan\Rules\Rule as PHPSanRule;

class Phpat
{
    /** @var array<SelectorInterface> */
    private array $subjects = [];
    /** @var array<SelectorInterface> */
    private array $targets = [];
    /** @var null|class-string<PHPSanRule> */
    private ?string $assertion = null;

    public static function rule(): self
    {
        return new Phpat();
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

    public function mustNotDependOn(): self
    {
        $this->assertion = MustNotDepend::class;

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
