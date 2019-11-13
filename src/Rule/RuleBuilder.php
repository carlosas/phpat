<?php declare(strict_types=1);

namespace PhpAT\Rule;

use PhpAT\Rule\Type\Composition;
use PhpAT\Rule\Type\Dependency;
use PhpAT\Rule\Type\Inheritance;
use PhpAT\Rule\Type\Mixin;
use PhpAT\Rule\Type\RuleType;
use PhpAT\Selector\SelectorInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

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

    public function __construct(ContainerBuilder $container)
    {
        $this->container = $container;
    }

    public function classesThat(SelectorInterface $selector): self
    {
        if (empty($this->type)) {
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
        if (empty($this->type)) {
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
        return $this->setType(Dependency::class, false);
    }

    public function mustNotDependOn(): self
    {
        return $this->setType(Dependency::class, true);
    }

    public function mustImplement(): self
    {
        return $this->setType(Composition::class, false);
    }

    public function mustNotImplement(): self
    {
        return $this->setType(Composition::class, true);
    }

    public function mustExtend(): self
    {
        return $this->setType(Inheritance::class, false);
    }

    public function mustNotExtend(): self
    {
        return $this->setType(Inheritance::class, true);
    }

    public function mustInclude(): self
    {
        return $this->setType(Mixin::class, false);
    }

    public function mustNotInclude(): self
    {
        return $this->setType(Mixin::class, true);
    }

    private function setType(string $ruleType, bool $inverse): self
    {
        $this->type = $this->container->get($ruleType);
        $this->inverse = $inverse;

        return $this;
    }

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
