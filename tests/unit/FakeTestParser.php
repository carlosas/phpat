<?php declare(strict_types=1);

namespace Tests\PHPat\unit;

use PHPat\Rule\Assertion\Constraint;
use PHPat\Selector\SelectorInterface;
use PHPat\Test\RelationRule;
use PHPat\Test\TestParser;

class FakeTestParser extends TestParser
{
    public string $ruleName;

    public Constraint $constraint;

    public string $assertionType;

    /** @var array<SelectorInterface> */
    public array $subjects;

    /** @var array<SelectorInterface> */
    public array $targets;

    /** @var array<string> */
    public array $tips = [];

    /** @var array<string, mixed> */
    public array $params = [];

    public function __invoke(): array
    {
        $rule = new RelationRule();
        $rule->ruleName = $this->ruleName;
        $rule->constraint = $this->constraint;
        $rule->assertionType = $this->assertionType;
        $rule->subjects = $this->subjects;
        $rule->targets = $this->targets;
        $rule->tips = $this->tips;
        $rule->params = $this->params;

        return [$rule];
    }

    /**
     * @param array<SelectorInterface> $subjects
     * @param array<SelectorInterface> $targets
     * @param array<string>            $tips
     * @param array<string, mixed>     $params
     */
    public static function create(string $ruleName, Constraint $constraint, string $assertionType, array $subjects, array $targets, array $tips = [], array $params = []): self
    {
        /** @var self $self */
        $self = (new \ReflectionClass(self::class))->newInstanceWithoutConstructor();
        $self->ruleName = $ruleName;
        $self->constraint = $constraint;
        $self->assertionType = $assertionType;
        $self->subjects = $subjects;
        $self->targets = $targets;
        $self->tips = $tips;
        $self->params = $params;

        return $self;
    }
}
