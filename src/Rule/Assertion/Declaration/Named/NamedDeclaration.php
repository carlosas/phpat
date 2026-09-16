<?php declare(strict_types=1);

namespace PHPat\Rule\Assertion\Declaration\Named;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Declaration\DeclarationAssertion;
use PHPat\Statement\StatementBuilder;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\FileTypeMapper;

abstract class NamedDeclaration extends DeclarationAssertion
{
    public function __construct(
        StatementBuilder $statementBuilder,
        Configuration $configuration,
        ReflectionProvider $reflectionProvider,
        FileTypeMapper $fileTypeMapper
    ) {
        parent::__construct(
            'beNamed',
            $statementBuilder,
            $configuration,
            $reflectionProvider,
            $fileTypeMapper
        );
    }

    protected function getMessage(string $ruleName, string $subject, Constraint $constraint, array $params = []): string
    {
        $negation = ($constraint === Constraint::Should) ? '' : ' not';

        $message = $params['isRegex'] === true
            ? sprintf('%s should%s be named matching the regex %s', $subject, $negation, $params['classname'])
            : sprintf('%s should%s be named %s', $subject, $negation, $params['classname']);

        return $this->prepareMessage($ruleName, $message);
    }
}
