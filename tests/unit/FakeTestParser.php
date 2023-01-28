<?php

declare(strict_types=1);

namespace Tests\PHPat\Unit;

use PHPat\Rule\Assertion\Assertion;
use PHPat\Selector\SelectorInterface;
use PHPat\Test\RelationRule;
use PHPat\Test\TestParser;
use ReflectionClass;

class FakeTestParser extends TestParser
{
    /** @var class-string<Assertion> */
    public string $assertion;
    /** @var array<SelectorInterface> */
    public array $subjects;
    /** @var array<SelectorInterface> */
    public array $targets;

    public function __invoke(): array
    {
        $rule            = new RelationRule();
        $rule->assertion = $this->assertion;
        $rule->subjects  = $this->subjects;
        $rule->targets   = $this->targets;

        return [$rule];
    }

    /**
     * @param class-string<Assertion> $assertion
     * @param array<SelectorInterface> $subjects
     * @param array<SelectorInterface> $targets
     */
    public static function create(string $assertion, array $subjects, array $targets): self
    {
        /** @var self $self */
        $self            = (new ReflectionClass(self::class))->newInstanceWithoutConstructor();
        $self->assertion = $assertion;
        $self->subjects  = $subjects;
        $self->targets   = $targets;

        return $self;
    }
}
