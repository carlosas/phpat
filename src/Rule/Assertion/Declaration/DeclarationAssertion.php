<?php declare(strict_types=1);

namespace PHPat\Rule\Assertion\Declaration;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Assertion;
use PHPat\Selector\SelectorInterface;
use PHPat\ShouldNotHappenException;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\RuleError;
use PHPStan\Type\FileTypeMapper;

abstract class DeclarationAssertion implements Assertion
{
    /** @var array<array{string, SelectorInterface, array<SelectorInterface>, array<string>, array<string, mixed>}> */
    protected array $statements;
    protected Configuration $configuration;
    protected ReflectionProvider $reflectionProvider;
    protected FileTypeMapper $fileTypeMapper;

    /**
     * @param class-string<DeclarationAssertion> $assertion
     */
    public function __construct(
        string $assertion,
        StatementBuilderFactory $statementBuilderFactory,
        Configuration $configuration,
        ReflectionProvider $reflectionProvider,
        FileTypeMapper $fileTypeMapper
    ) {
        $this->statements = $statementBuilderFactory->create($assertion)->build();
        $this->configuration = $configuration;
        $this->reflectionProvider = $reflectionProvider;
        $this->fileTypeMapper = $fileTypeMapper;
    }

    /**
     * @throws ShouldNotHappenException
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if (!$this->ruleApplies($scope)) {
            return [];
        }

        $meetsDeclaration = $this->meetsDeclaration($node, $scope);

        return $this->validateGetErrors($scope, $meetsDeclaration);
    }

    public function prepareMessage(string $ruleName, string $message): string
    {
        return $this->configuration->showRuleNames()
            ? sprintf('%s: %s', $ruleName, $message)
            : $message;
    }

    abstract protected function meetsDeclaration(Node $node, Scope $scope, array $params = []): bool;

    /**
     * @param class-string $subject
     */
    abstract protected function getMessage(string $ruleName, string $subject, array $params = []): string;

    /**
     * @param  array<string>    $tips
     * @return array<RuleError>
     */
    abstract protected function applyValidation(string $ruleName, ClassReflection $subject, bool $meetsDeclaration, array $tips): array;

    protected function ruleApplies(Scope $scope): bool
    {
        if (!$scope->isInClass()) {
            return false;
        }

        return $scope->getClassReflection() !== null;
    }

    /**
     * @return array<RuleError>
     * @throws ShouldNotHappenException
     */
    protected function validateGetErrors(Scope $scope, bool $meetsDeclaration): array
    {
        $errors = [];
        $subject = $scope->getClassReflection();
        if ($subject === null) {
            throw new ShouldNotHappenException();
        }

        foreach ($this->statements as [$ruleName, $selector, $subjectExcludes, $tips]) {
            if ($subject->isBuiltin() || !$selector->matches($subject)) {
                continue;
            }
            foreach ($subjectExcludes as $exclude) {
                if ($exclude->matches($subject)) {
                    continue 2;
                }
            }

            array_push($errors, ...$this->applyValidation($ruleName, $subject, $meetsDeclaration, $tips));
        }

        return $errors;
    }
}
