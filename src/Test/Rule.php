<?php declare(strict_types=1);

namespace PHPat\Test;

use PHPat\Rule\Assertion\Constraint;
use PHPat\Selector\SelectorInterface;

interface Rule
{
    public function getConstraint(): ?Constraint;

    public function getAssertionType(): ?string;

    /**
     * @return array<SelectorInterface>
     */
    public function getSubjects(): array;

    /**
     * @return array<SelectorInterface>
     */
    public function getSubjectExcludes(): array;

    /**
     * @return array<SelectorInterface>
     */
    public function getTargets(): array;

    /**
     * @return array<SelectorInterface>
     */
    public function getTargetExcludes(): array;

    public function getRuleName(): string;

    /**
     * @return array<string>
     */
    public function getTips(): array;

    /**
     * @return array<string, mixed>
     */
    public function getParams(): array;
}
