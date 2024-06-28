<?php declare(strict_types=1);

namespace PHPat\Rule\Assertion\Declaration\ShouldNotExist;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Declaration\DeclarationAssertion;
use PHPat\Rule\Assertion\Declaration\ValidationTrait;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PHPStan\Node\InClassNode;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\FileTypeMapper;

abstract class ShouldNotExist extends DeclarationAssertion
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

    public function getNodeType(): string
    {
        return InClassNode::class;
    }

    protected function applyValidation(string $ruleName, ClassReflection $subject, bool $meetsDeclaration, array $tips, array $params = []): array
    {
        return $this->applyShouldNot($ruleName, $subject, $meetsDeclaration, $tips, $params);
    }
}