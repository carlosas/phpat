<?php declare(strict_types=1);

namespace PHPat\Rule\Assertion\Declaration\IsInvokable;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Declaration\DeclarationAssertion;
use PHPat\Statement\StatementBuilder;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\FileTypeMapper;

abstract class InvokableDeclaration extends DeclarationAssertion
{
    public function __construct(
        StatementBuilder $statementBuilder,
        Configuration $configuration,
        ReflectionProvider $reflectionProvider,
        FileTypeMapper $fileTypeMapper
    ) {
        parent::__construct(
            'beInvokable',
            $statementBuilder,
            $configuration,
            $reflectionProvider,
            $fileTypeMapper
        );
    }

    protected function getMessage(string $ruleName, string $subject, Constraint $constraint, array $params = []): string
    {
        $negation = ($constraint === Constraint::Should) ? '' : ' not';

        return $this->prepareMessage(
            $ruleName,
            sprintf('%s should%s be invokable', $subject, $negation)
        );
    }
}
