<?php declare(strict_types=1);

namespace PHPat\Test;

use PHPat\Rule\Assertion\Constraint;
use PHPat\Selector\SelectorInterface;

final class RelationRule implements Rule
{
    /** @var array<SelectorInterface> */
    public array $subjects = [];

    /** @var array<SelectorInterface> */
    public array $subjectExcludes = [];

    /** @var array<SelectorInterface> */
    public array $targets = [];

    /** @var array<SelectorInterface> */
    public array $targetExcludes = [];

    public ?Constraint $constraint = null;

    public ?string $assertionType = null;

    public string $ruleName = '';

    /** @var array<string> */
    public array $tips = [];

    /** @var array<string, mixed> */
    public array $params = [];

    public function getConstraint(): ?Constraint
    {
        return $this->constraint;
    }

    public function getAssertionType(): ?string
    {
        return $this->assertionType;
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

    public function getRuleName(): string
    {
        return $this->ruleName;
    }

    public function getTips(): array
    {
        return $this->tips;
    }

    public function getParams(): array
    {
        return $this->params;
    }
}
