<?php

declare(strict_types=1);

namespace PHPat\Rule\Assertion\ShouldNotDepend;

use PHPat\Rule\Assertion\Assertion;
use PHPat\Rule\Assertion\ValidationTrait;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Type\FileTypeMapper;

abstract class ShouldNotDepend extends Assertion
{
    use ValidationTrait;

    public function __construct(
        StatementBuilderFactory $statementBuilderFactory,
        ReflectionProvider $reflectionProvider,
        FileTypeMapper $fileTypeMapper
    ) {
        parent::__construct(__CLASS__, $statementBuilderFactory, $reflectionProvider, $fileTypeMapper);
    }

    protected function applyValidation(ClassReflection $subject, array $targets, array $nodes): array
    {
        return $this->applyShouldNot($subject, $targets, $nodes);
    }

    protected function getMessage(string $subject, string $target): string
    {
        return sprintf('%s should not depend on %s', $subject, $target);
    }
}
