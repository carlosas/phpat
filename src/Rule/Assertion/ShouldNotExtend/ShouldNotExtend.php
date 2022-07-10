<?php

declare(strict_types=1);

namespace PHPat\Rule\Assertion\ShouldNotExtend;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Assertion;
use PHPat\Rule\Assertion\ValidationTrait;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\FileTypeMapper;

abstract class ShouldNotExtend extends Assertion
{
    use ValidationTrait;

    public function __construct(
        StatementBuilderFactory $statementBuilderFactory,
        Configuration $configuration,
        ReflectionProvider $reflectionProvider,
        FileTypeMapper $fileTypeMapper
    ) {
        parent::__construct(
            __CLASS__,
            $statementBuilderFactory,
            $configuration,
            $reflectionProvider,
            $fileTypeMapper
        );
    }

    protected function applyValidation(ClassReflection $subject, array $targets, array $nodes): array
    {
        return $this->applyShouldNot($subject, $targets, $nodes);
    }

    protected function getMessage(string $subject, string $target): string
    {
        return sprintf('%s should not extend %s', $subject, $target);
    }
}
