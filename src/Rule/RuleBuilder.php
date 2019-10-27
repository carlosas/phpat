<?php

declare(strict_types=1);

namespace PhpAT\Rule;

use PhpAT\Rule\Type\Composition;
use PhpAT\Rule\Type\Dependency;
use PhpAT\Rule\Type\Inheritance;
use PhpAT\Rule\Type\RuleType;
use PhpAT\Selector\SelectorInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class RuleBuilder
 * @package PhpAT\Rule
 */
class RuleBuilder
{
    /** @var ContainerBuilder */
    private $container;
    /** @var SelectorInterface[] */
    private $origin = [];
    /** @var SelectorInterface[] */
    private $originExclude = [];
    /** @var SelectorInterface[] */
    private $destination = [];
    /** @var SelectorInterface[] */
    private $destinationExclude = [];
    /** @var RuleType */
    private $type = '';
    /** @var bool */
    private $inverse = false;

    /**
     * RuleBuilder constructor.
     * @param ContainerBuilder $container
     */
    public function __construct(ContainerBuilder $container)
    {
        $this->container = $container;
    }

    /**
     * @param SelectorInterface $selector
     * @return RuleBuilder
     */
    public function classesThat(SelectorInterface $selector): self
    {
        if (empty($this->type)) {
            $this->origin[] = $selector;
        } else {
            $this->destination[] = $selector;
        }

        return $this;
    }

    /**
     * @param SelectorInterface $selector
     * @return RuleBuilder
     */
    public function andClassesThat(SelectorInterface $selector): self
    {
        return $this->classesThat($selector);
    }

    /**
     * @param SelectorInterface $selector
     * @return RuleBuilder
     */
    public function excludingClassesThat(SelectorInterface $selector): self
    {
        if (empty($this->type)) {
            $this->originExclude[] = $selector;
        } else {
            $this->destinationExclude[] = $selector;
        }

        return $this;
    }

    /**
     * @param SelectorInterface $selector
     * @return RuleBuilder
     */
    public function andExcludingClassesThat(SelectorInterface $selector): self
    {
        return $this->excludingClassesThat($selector);
    }

    /**
     * @return RuleBuilder
     */
    public function shouldDependOn(): self
    {
        return $this->setType(Dependency::class, false);
    }

    /**
     * @return RuleBuilder
     */
    public function shouldNotDependOn(): self
    {
        return $this->setType(Dependency::class, true);
    }

    /**
     * @return RuleBuilder
     */
    public function shouldImplement(): self
    {
        return $this->setType(Composition::class, false);
    }

    /**
     * @return RuleBuilder
     */
    public function shouldNotImplement(): self
    {
        return $this->setType(Composition::class, true);
    }

    /**
     * @return RuleBuilder
     */
    public function shouldExtend(): self
    {
        return $this->setType(Inheritance::class, false);
    }

    /**
     * @return RuleBuilder
     */
    public function shouldNotExtend(): self
    {
        return $this->setType(Inheritance::class, true);
    }

    /**
     * @return RuleBuilder
     */
    private function setType(string $ruleType, bool $inverse): self
    {
        $this->type = $this->container->get($ruleType);
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
            $this->type,
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
        $this->type = '';
        $this->inverse = false;
    }
}
