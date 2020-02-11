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
        return $this->setAssertion(Dependency\MustDepend::class);
    }

    /**
     * @return RuleBuilder
     */
    public function mustNotDependOn(): self
    {
        return $this->setAssertion(Dependency\MustNotDepend::class);
    }

    /**
     * @return RuleBuilder
     */
    public function mustOnlyDependOn(): self
    {
        return $this->setAssertion(Dependency\MustOnlyDepend::class);
    }

    /**
     * @return RuleBuilder
     */
    public function canOnlyDependOn(): self
    {
        return $this->setAssertion(Dependency\CanOnlyDepend::class);
    }

    /**
     * @return RuleBuilder
     */
    public function mustImplement(): self
    {
        return $this->setAssertion(Composition\MustImplement::class);
    }

    /**
     * @return RuleBuilder
     */
    public function mustNotImplement(): self
    {
        return $this->setAssertion(Composition\MustNotImplement::class);
    }

    /**
     * @return RuleBuilder
     */
    public function mustOnlyImplement(): self
    {
        return $this->setAssertion(Composition\MustOnlyImplement::class);
    }

    /**
     * @return RuleBuilder
     */
    public function canOnlyImplement(): self
    {
        return $this->setAssertion(Composition\CanOnlyImplement::class);
    }

    /**
     * @return RuleBuilder
     */
    public function mustExtend(): self
    {
        return $this->setAssertion(Inheritance\MustExtend::class);
    }

    /**
     * @return RuleBuilder
     */
    public function mustNotExtend(): self
    {
        return $this->setAssertion(Inheritance\MustNotExtend::class);
    }

    /**
     * @return RuleBuilder
     */
    public function canOnlyExtend(): self
    {
        return $this->setAssertion(Inheritance\CanOnlyExtend::class);
    }

    /**
     * @return RuleBuilder
     */
    public function mustInclude(): self
    {
        return $this->setAssertion(Mixin\MustInclude::class);
    }

    /**
     * @return RuleBuilder
     */
    public function mustNotInclude(): self
    {
        return $this->setAssertion(Mixin\MustNotInclude::class);
    }

    /**
     * @return RuleBuilder
     */
    public function mustOnlyInclude(): self
    {
        return $this->setAssertion(Mixin\MustOnlyInclude::class);
    }

    /**
     * @return RuleBuilder
     */
    public function canOnlyInclude(): self
    {
        return $this->setAssertion(Mixin\CanOnlyInclude::class);
    }

    /**
     * @param string $assertion
     * @return RuleBuilder
     */
    private function setAssertion(string $assertion): self
    {
        $this->assertion = $this->container->get($assertion);

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
    }
}
