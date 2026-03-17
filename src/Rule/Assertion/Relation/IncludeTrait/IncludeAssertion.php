<?php declare(strict_types=1);

namespace PHPat\Rule\Assertion\Relation\IncludeTrait;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Relation\RelationAssertion;
use PHPat\Statement\StatementBuilder;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\FileTypeMapper;

abstract class IncludeAssertion extends RelationAssertion
{
    public function __construct(
        StatementBuilder $statementBuilder,
        Configuration $configuration,
        ReflectionProvider $reflectionProvider,
        FileTypeMapper $fileTypeMapper
    ) {
        parent::__construct(
            'include',
            $statementBuilder,
            $configuration,
            $reflectionProvider,
            $fileTypeMapper
        );
    }

    protected function getRelationVerb(): string
    {
        return 'include';
    }
}
