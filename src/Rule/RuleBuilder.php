<?php declare(strict_types=1);

namespace PhpAT\Rule;

use PhpAT\Rule\Type\Composition;
use PhpAT\Rule\Type\Dependency;
use PhpAT\Rule\Type\Inheritance;
use Psr\Container\ContainerInterface;

class RuleBuilder
{
    private $container;
    private $origin = [];
    private $originExclude = [];
    private $destination = [];
    private $destinationExclude = [];
    private $type;
    private $inverse;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function classesThat(string $selector): self
    {
        if (empty($this->type)) {
            $this->origin[] = $selector;
        } else {
            $this->destination[] = $selector;
        }

        return $this;
    }

    public function andClassesThat(string $selector): self
    {
        return $this->classesThat($selector);
    }

    public function excludingClassesThat(string $selector): self
    {
        if (empty($this->type)) {
            $this->originExclude[] = $selector;
        } else {
            $this->destinationExclude[] = $selector;
        }

        return $this;
    }

    public function andExcludingClassesThat(string $selector): self
    {
        return $this->excludingClassesThat($selector);
    }

    public function shouldDependOn(): self
    {
        return $this->setType(Dependency::class, false);
    }

    public function shouldNotDependOn(): self
    {
        return $this->setType(Dependency::class, true);
    }

    public function shouldImplement(): self
    {
        return $this->setType(Composition::class, false);
    }

    public function shouldNotImplement(): self
    {
        return $this->setType(Composition::class, true);
    }

    public function shouldExtend(): self
    {
        return $this->setType(Inheritance::class, false);
    }

    public function shouldNotExtend(): self
    {
        return $this->setType(Inheritance::class, true);
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
        $this->type = null;
        $this->inverse = null;
    }
}
