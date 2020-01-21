<?php

declare(strict_types=1);

namespace PhpAT\Rule;

use PhpAT\Rule\Assertion\Composition;
use PhpAT\Rule\Assertion\Dependency;
use PhpAT\Rule\Assertion\Inheritance;
use PhpAT\Rule\Assertion\Mixin;
use PhpAT\Rule\Assertion\Assertion;
use PhpAT\Selector\SelectorInterface;
use Psr\Container\ContainerInterface;

/**
 * Class RuleBuilder
 *
 * @package PhpAT\Rule
 */
class RuleBuilder
{
    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var SelectorInterface[]
     */
    private $origin = [];
    /**
     * @var SelectorInterface[]
     */
    private $originExclude = [];
    /**
     * @var SelectorInterface[]
     */
    private $destination = [];
    /**
     * @var SelectorInterface[]
     */
    private $destinationExclude = [];
    /**
     * @var Assertion|null
     */
    private $assertion = null;
    /**
     * @var bool
     */
    private $inverse = false;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param  SelectorInterface $selector
     * @return RuleBuilder
     */
    public function classesThat(SelectorInterface $selector): self
    {
        if (empty($this->assertion)) {
            $this->origin[] = $selector;
        } else {
            $this->destination[] = $selector;
        }

        return $this;
    }

    /**
     * @param  SelectorInterface $selector
     * @return RuleBuilder
     */
    public function andClassesThat(SelectorInterface $selector): self
    {
        return $this->classesThat($selector);
    }

    /**
     * @param  SelectorInterface $selector
     * @return RuleBuilder
     */
    public function excludingClassesThat(SelectorInterface $selector): self
    {
        if (empty($this->assertion)) {
            $this->originExclude[] = $selector;
        } else {
            $this->destinationExclude[] = $selector;
        }

        return $this;
    }

    /**
     * @param  SelectorInterface $selector
     * @return RuleBuilder
     */
    public function andExcludingClassesThat(SelectorInterface $selector): self
    {
        return $this->excludingClassesThat($selector);
    }

    /**
     * @return RuleBuilder
     */
    public function mustDependOn(): self
    {
        return $this->setAssertion(Dependency\MustDepend::class, false);
    }

    /**
     * @return RuleBuilder
     */
    public function mustNotDependOn(): self
    {
        return $this->setAssertion(Dependency\MustDepend::class, true);
    }

    /**
     * @return RuleBuilder
     */
    public function mustOnlyDependOn(): self
    {
        return $this->setAssertion(Dependency\MustOnlyDepend::class, false);
    }

    /**
     * @return RuleBuilder
     */
    public function canOnlyDependOn(): self
    {
        return $this->setAssertion(Dependency\CanOnlyDepend::class, false);
    }

    /**
     * @return RuleBuilder
     */
    public function mustImplement(): self
    {
        return $this->setAssertion(Composition\MustImplement::class, false);
    }

    /**
     * @return RuleBuilder
     */
    public function mustNotImplement(): self
    {
        return $this->setAssertion(Composition\MustImplement::class, true);
    }

    /**
     * @return RuleBuilder
     */
    public function mustOnlyImplement(): self
    {
        return $this->setAssertion(Composition\MustOnlyImplement::class, false);
    }

    /**
     * @return RuleBuilder
     */
    public function canOnlyImplement(): self
    {
        return $this->setAssertion(Composition\CanOnlyImplement::class, false);
    }

    /**
     * @return RuleBuilder
     */
    public function mustExtend(): self
    {
        return $this->setAssertion(Inheritance\MustExtend::class, false);
    }

    /**
     * @return RuleBuilder
     */
    public function mustNotExtend(): self
    {
        return $this->setAssertion(Inheritance\MustExtend::class, true);
    }

    /**
     * @return RuleBuilder
     */
    public function canOnlyExtend(): self
    {
        return $this->setAssertion(Inheritance\CanOnlyExtend::class, false);
    }

    /**
     * @return RuleBuilder
     */
    public function mustInclude(): self
    {
        return $this->setAssertion(Mixin\MustInclude::class, false);
    }

    /**
     * @return RuleBuilder
     */
    public function mustNotInclude(): self
    {
        return $this->setAssertion(Mixin\MustInclude::class, true);
    }

    /**
     * @return RuleBuilder
     */
    public function mustOnlyInclude(): self
    {
        return $this->setAssertion(Mixin\MustOnlyInclude::class, false);
    }

    /**
     * @return RuleBuilder
     */
    public function canOnlyInclude(): self
    {
        return $this->setAssertion(Mixin\CanOnlyInclude::class, false);
    }

    /**
     * @param string $assertion
     * @param bool   $inverse
     * @return RuleBuilder
     */
    private function setAssertion(string $assertion, bool $inverse): self
    {
        $this->assertion = $this->container->get($assertion);
        $this->inverse = $inverse;

        return $this;
    }

    /**
     * @return Rule
     */
    public function build(): Rule
    {
        $rule = new Rule(
            $this->origin,
            $this->originExclude,
            $this->assertion,
            $this->inverse,
            $this->destination,
            $this->destinationExclude
        );
        $this->resetBuilder();

        return $rule;
    }

    private function resetBuilder(): void
    {
        $this->origin = [];
        $this->originExclude = [];
        $this->destination = [];
        $this->destinationExclude = [];
        $this->assertion = null;
        $this->inverse = false;
    }
}
