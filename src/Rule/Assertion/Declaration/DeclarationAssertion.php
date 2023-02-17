<?php

declare(strict_types=1);

namespace PHPat\Rule\Assertion\Declaration;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Assertion;
use PHPat\Selector\SelectorInterface;
use PHPat\ShouldNotHappenException;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PHPat\Test\TestName;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\RuleError;
use PHPStan\Type\FileTypeMapper;

abstract class DeclarationAssertion implements Assertion
{
    /** @var array<array{TestName, SelectorInterface, array<SelectorInterface>, array<SelectorInterface>, array<SelectorInterface>}> */
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
        $this->statements         = $statementBuilderFactory->create($assertion)->build();
        $this->configuration      = $configuration;
        $this->reflectionProvider = $reflectionProvider;
        $this->fileTypeMapper     = $fileTypeMapper;
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

    abstract protected function meetsDeclaration(Node $node, Scope $scope): bool;

    /**
     * @param TestName $testName
     * @param class-string $subject
     */
    abstract protected function getMessage(TestName $testName, string $subject): string;

    /**
     * @return array<RuleError>
     */
    abstract protected function applyValidation(TestName $testName, ClassReflection $subject, bool $meetsDeclaration): array;

    protected function ruleApplies(Scope $scope): bool
    {
        if (!($scope->isInClass())) {
            return false;
        }

        return $scope->getClassReflection() !== null;
    }

    /**
     * @throws ShouldNotHappenException
     * @return array<RuleError>
     */
    protected function validateGetErrors(Scope $scope, bool $meetsDeclaration): array
    {
        $errors  = [];
        $subject = $scope->getClassReflection();
        if ($subject === null) {
            throw new ShouldNotHappenException();
        }
        foreach ($this->statements as [$testName, $selector, $subjectExcludes]) {
            if ($subject->isBuiltin() || !$selector->matches($subject)) {
                continue;
            }
            foreach ($subjectExcludes as $exclude) {
                if ($exclude->matches($subject)) {
                    continue 2;
                }
            }

            array_push($errors, ...$this->applyValidation($testName, $subject, $meetsDeclaration));
        }

        return $errors;
    }
}
