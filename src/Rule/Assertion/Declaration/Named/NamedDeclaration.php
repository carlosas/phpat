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
        $message = $params['isRegex'] === true
            ? sprintf('%s should be named matching the regex %s', $subject, $params['classname'])
            : sprintf('%s should be named %s', $subject, $params['classname']);

        return $this->prepareMessage($ruleName, $message);
    }
}
