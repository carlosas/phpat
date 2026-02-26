<?php declare(strict_types=1);

namespace PHPat\Statement;

use PHPat\Rule\Assertion\Constraint;
use PHPat\Selector\SelectorInterface;

final class Statement
{
    public string $ruleName;
    public Constraint $constraint;
    public SelectorInterface $subject;

    /** @var array<SelectorInterface> */
    public array $subjectExcludes;

    /** @var array<SelectorInterface> */
    public array $targets;

    /** @var array<SelectorInterface> */
    public array $targetExcludes;

    /** @var array<string> */
    public array $tips;

    /** @var array<string, mixed> */
    public array $params;

    /**
     * @param array<SelectorInterface> $subjectExcludes
     * @param array<SelectorInterface> $targets
     * @param array<SelectorInterface> $targetExcludes
     * @param array<string>            $tips
     * @param array<string, mixed>     $params
     */
    public function __construct(
        string $ruleName,
        Constraint $constraint,
        SelectorInterface $subject,
        array $subjectExcludes,
        array $targets,
        array $targetExcludes,
        array $tips,
        array $params
    ) {
        $this->ruleName = $ruleName;
        $this->constraint = $constraint;
        $this->subject = $subject;
        $this->subjectExcludes = $subjectExcludes;
        $this->targets = $targets;
        $this->targetExcludes = $targetExcludes;
        $this->tips = $tips;
        $this->params = $params;
    }
}
