<?php

declare(strict_types=1);

namespace PHPat\Test;

use PHPat\Rule\Assertion\Declaration\DeclarationAssertion;
use PHPat\Rule\Assertion\Relation\RelationAssertion;
use PHPat\Selector\SelectorInterface;

class RelationRule implements Rule
{
    /** @var array<SelectorInterface> */
    public array $subjects = [];
    /** @var array<SelectorInterface> */
    public array $subjectExcludes = [];
    /** @var array<SelectorInterface> */
    public array $targets = [];
    /** @var array<SelectorInterface> */
    public array $targetExcludes = [];
    /** @var null|class-string<RelationAssertion>|class-string<DeclarationAssertion> */
    public ?string $assertion = null;

    /**
     * @return null|class-string<RelationAssertion>|class-string<DeclarationAssertion>
     */
    public function getAssertion(): ?string
    {
        return $this->assertion;
    }

    public function getSubjects(): array
    {
        return $this->subjects;
    }

    public function getSubjectExcludes(): array
    {
        return $this->subjectExcludes;
    }

    public function getTargets(): array
    {
        return $this->targets;
    }

    public function getTargetExcludes(): array
    {
        return $this->targetExcludes;
    }
}
