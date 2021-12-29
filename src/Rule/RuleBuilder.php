<?php

declare(strict_types=1);

namespace PhpAT\Rule;

use PhpAT\Rule\Assertion\Composition;
use PhpAT\Rule\Assertion\Dependency;
use PhpAT\Rule\Assertion\Inheritance;
use PhpAT\Rule\Assertion\Mixin;
use PhpAT\Rule\Assertion\AbstractAssertion;
use PhpAT\Selector\SelectorInterface;
use Psr\Container\ContainerInterface;

/**
 * Class RuleBuilder
 *
 * @package PhpAT\Rule
 */
class RuleBuilder
{
    private ContainerInterface $container;
    private ?AbstractAssertion $assertion;
    /** @var array<SelectorInterface> */
    private array $origin = [];
    /** @var array<SelectorInterface> */
    private array $originExclude = [];
    /** @var array<SelectorInterface> */
    private array $destination = [];
    /** @var array<SelectorInterface> */
    private array $destinationExclude = [];

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function classesThat(SelectorInterface $selector): self
    {
        if (empty($this->assertion)) {
            $this->origin[] = $selector;
        } else {
            $this->destination[] = $selector;
        }

        return $this;
    }

    public function andClassesThat(SelectorInterface $selector): self
    {
        return $this->classesThat($selector);
    }

    public function excludingClassesThat(SelectorInterface $selector): self
    {
        if (empty($this->assertion)) {
            $this->originExclude[] = $selector;
        } else {
            $this->destinationExclude[] = $selector;
        }

        return $this;
    }

    public function andExcludingClassesThat(SelectorInterface $selector): self
    {
        return $this->excludingClassesThat($selector);
    }

    public function mustDependOn(): self
    {
        return $this->setAssertion(Dependency\MustDepend::class);
    }

    public function mustNotDependOn(): self
    {
        return $this->setAssertion(Dependency\MustNotDepend::class);
    }

    public function mustOnlyDependOn(): self
    {
        return $this->setAssertion(Dependency\MustOnlyDepend::class);
    }

    public function canOnlyDependOn(): self
    {
        return $this->setAssertion(Dependency\CanOnlyDepend::class);
    }

    public function mustImplement(): self
    {
        return $this->setAssertion(Composition\MustImplement::class);
    }

    public function mustNotImplement(): self
    {
        return $this->setAssertion(Composition\MustNotImplement::class);
    }

    public function mustOnlyImplement(): self
    {
        return $this->setAssertion(Composition\MustOnlyImplement::class);
    }

    public function canOnlyImplement(): self
    {
        return $this->setAssertion(Composition\CanOnlyImplement::class);
    }

    public function mustExtend(): self
    {
        return $this->setAssertion(Inheritance\MustExtend::class);
    }

    public function mustNotExtend(): self
    {
        return $this->setAssertion(Inheritance\MustNotExtend::class);
    }

    public function canOnlyExtend(): self
    {
        return $this->setAssertion(Inheritance\CanOnlyExtend::class);
    }

    public function mustInclude(): self
    {
        return $this->setAssertion(Mixin\MustInclude::class);
    }

    public function mustNotInclude(): self
    {
        return $this->setAssertion(Mixin\MustNotInclude::class);
    }

    public function mustOnlyInclude(): self
    {
        return $this->setAssertion(Mixin\MustOnlyInclude::class);
    }

    public function canOnlyInclude(): self
    {
        return $this->setAssertion(Mixin\CanOnlyInclude::class);
    }

    private function setAssertion(string $assertion): self
    {
        $this->assertion = $this->container->get($assertion);

        return $this;
    }

    public function build(): Rule
    {
        $rule = new Rule(
            $this->origin,
            $this->originExclude,
            $this->assertion,
            $this->destination,
            $this->destinationExclude
        );
        $this->resetBuilder();

        return $rule;
    }

    private function resetBuilder(): void
    {
        $this->origin             = [];
        $this->originExclude      = [];
        $this->destination        = [];
        $this->destinationExclude = [];
        $this->assertion          = null;
    }
}
