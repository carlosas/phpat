<?php

declare(strict_types=1);

namespace PHPatOld\Rule;

use PHPatOld\Rule\Assertion\AbstractAssertion;
use PHPatOld\Rule\Assertion\Composition;
use PHPatOld\Rule\Assertion\Dependency;
use PHPatOld\Rule\Assertion\Inheritance;
use PHPatOld\Rule\Assertion\Mixin;
use PHPatOld\Selector\Selector;
use Psr\Container\ContainerInterface;

/**
 * Class RuleBuilder
 *
 * @package PHPat\Rule
 */
class RuleBuilder
{
    private ContainerInterface $container;
    private ?AbstractAssertion $assertion;
    /** @var array<Selector> */
    private array $origin = [];
    /** @var array<Selector> */
    private array $originExclude = [];
    /** @var array<Selector> */
    private array $destination = [];
    /** @var array<Selector> */
    private array $destinationExclude = [];

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function classesThat(Selector $selector): self
    {
        if (empty($this->assertion)) {
            $this->origin[] = $selector;
        } else {
            $this->destination[] = $selector;
        }

        return $this;
    }

    public function andClassesThat(Selector $selector): self
    {
        return $this->classesThat($selector);
    }

    public function excludingClassesThat(Selector $selector): self
    {
        if (empty($this->assertion)) {
            $this->originExclude[] = $selector;
        } else {
            $this->destinationExclude[] = $selector;
        }

        return $this;
    }

    public function andExcludingClassesThat(Selector $selector): self
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

    private function setAssertion(string $assertion): self
    {
        $this->assertion = $this->container->get($assertion);

        return $this;
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
