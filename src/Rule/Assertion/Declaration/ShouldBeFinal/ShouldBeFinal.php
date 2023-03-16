<?php

declare(strict_types=1);

namespace PHPat\Rule\Assertion\Declaration\ShouldBeFinal;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Declaration\DeclarationAssertion;
use PHPat\Rule\Assertion\Declaration\ValidationTrait;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\FileTypeMapper;

abstract class ShouldBeFinal extends DeclarationAssertion
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

    protected function applyValidation(string $ruleName, ClassReflection $subject, bool $meetsDeclaration): array
    {
        return $this->applyShould($ruleName, $subject, $meetsDeclaration);
    }

    protected function getMessage(string $ruleName, string $subject): string
    {
        return strtr(
            $this->configuration->getDeclarationMessageFormat(),
            [
                '{subject}'     => $subject,
                '{declaration}' => 'should be final',
                '{ruleName}'    => $ruleName,
            ]
        );
    }
}
