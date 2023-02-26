<?php

declare(strict_types=1);

namespace PHPat\Rule\Assertion\Relation\ShouldNotImplement;

use PHPat\Configuration;
use PHPat\Rule\Assertion\AssertionType;
use PHPat\Rule\Assertion\Relation\RelationAssertion;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\FileTypeMapper;

abstract class ShouldNotImplement extends RelationAssertion
{
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

    public function getType(): string
    {
        return AssertionType::SHOULD_NOT;
    }

    protected function getMessage(string $subject, string $target): string
    {
        return sprintf('%s should not implement %s', $subject, $target);
    }
}
