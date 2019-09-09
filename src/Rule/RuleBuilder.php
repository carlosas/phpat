<?php declare(strict_types=1);

namespace PhpAT\Rule;

use Psr\Container\ContainerInterface;

class RuleBuilder
{
    private $container;
    private $origin;
    private $params = [];
    private $exclude = [];
    private $type;
    private $inverse;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function filesLike(string $origin): self
    {
        $this->origin = $origin;

        return $this;
    }

    public function withParams(array $params): self
    {
        $this->params = $params;

        return $this;
    }

    public function excluding(string $excluding): self
    {
        $this->exclude[] = $excluding;

        return $this;
    }

    public function shouldHave(string $type): self
    {
        $this->type = $this->container->get($type);
        $this->inverse = false;

        return $this;
    }

    public function shouldNotHave(string $type): self
    {
        $this->type = $this->container->get($type);
        $this->inverse = true;

        return $this;
    }

    public function build(): Rule
    {
        $rule = new Rule(
            $this->origin,
            $this->type,
            $this->inverse,
            $this->params,
            '',
            $this->exclude
        );
        $this->resetBuilder();

        return $rule;
    }

    private function resetBuilder(): void
    {
        $this->origin = null;
        $this->params = null;
        $this->exclude = [];
        $this->type = null;
        $this->inverse = null;
    }
}
