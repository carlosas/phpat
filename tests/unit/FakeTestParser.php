<?php

declare(strict_types=1);

namespace Tests\PHPat\unit;

use PHPat\Rule\Assertion\Assertion;
use PHPat\Selector\SelectorInterface;
use PHPat\Test\RelationRule;
use PHPat\Test\TestParser;
use ReflectionClass;

class FakeTestParser extends TestParser
{
    /** @var string */
    public string $ruleName;
    /** @var class-string<Assertion> */
    public string $assertion;
    /** @var array<SelectorInterface> */
    public array $subjects;
    /** @var array<SelectorInterface> */
    public array $targets;
    /** @var string[] */
    public array $tips = [];

    public function __invoke(): array
    {
        $rule            = new RelationRule();
        $rule->ruleName  = $this->ruleName;
        $rule->assertion = $this->assertion;
        $rule->subjects  = $this->subjects;
        $rule->targets   = $this->targets;
        $rule->tips      = $this->tips;

        return [$rule];
    }

    /**
     * @param class-string<Assertion> $assertion
     * @param array<SelectorInterface> $subjects
     * @param array<SelectorInterface> $targets
     * @param string[] $tips
     */
    public static function create(string $ruleName, string $assertion, array $subjects, array $targets, array $tips = []): self
    {
        /** @var self $self */
        $self            = (new ReflectionClass(self::class))->newInstanceWithoutConstructor();
        $self->ruleName  = $ruleName;
        $self->assertion = $assertion;
        $self->subjects  = $subjects;
        $self->targets   = $targets;
        $self->tips      = $tips;

        return $self;
    }
}
