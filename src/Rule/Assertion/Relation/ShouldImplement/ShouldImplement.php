<?php

declare(strict_types=1);

namespace PHPat\Rule\Assertion\Relation\ShouldImplement;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Relation\Assertion;
use PHPat\Rule\Assertion\Relation\ValidationTrait;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\FileTypeMapper;

abstract class ShouldImplement extends Assertion
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
        return $this->applyShould($subject, $targets, $nodes);
    }

    protected function getMessage(string $subject, string $target): string
    {
        return sprintf('%s should implement %s', $subject, $target);
    }
}
