<?php

declare(strict_types=1);

namespace PHPat\Rule\Assertion\Relation;

use PHPat\Configuration;
use PHPat\Selector\Classname;
use PHPat\Selector\SelectorInterface;
use PHPat\ShouldNotHappenException;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\Rule as PHPStanRule;
use PHPStan\Rules\RuleError;
use PHPStan\Type\FileTypeMapper;

/**
 * @implements PHPStanRule<Node>
 */
abstract class RelationAssertion implements PHPStanRule
{
    /** @var array<array{SelectorInterface, array<SelectorInterface>, array<SelectorInterface>, array<SelectorInterface>}> */
    protected array $statements;
    protected Configuration $configuration;
    protected ReflectionProvider $reflectionProvider;
    protected FileTypeMapper $fileTypeMapper;

    /**
     * @param class-string<RelationAssertion> $assertion
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
        $nodes = $this->extractNodeClassNames($node, $scope);

        if (!$this->ruleApplies($scope, $nodes)) {
            return [];
        }

        return $this->validateGetErrors($scope, $nodes);
    }

    /**
     * @return array<class-string>
     */
    abstract protected function extractNodeClassNames(Node $node, Scope $scope): array;

    /**
     * @param class-string $subject
     */
    abstract protected function getMessage(string $subject, string $target): string;

    /**
     * @param array<SelectorInterface> $targets
     * @param array<SelectorInterface> $targetExcludes
     * @param array<class-string> $nodes
     * @return array<RuleError>
     */
    abstract protected function applyValidation(ClassReflection $subject, array $targets, array $targetExcludes, array $nodes): array;

    /**
     * @param array<class-string> $nodes
     */
    protected function ruleApplies(Scope $scope, array $nodes): bool
    {
        if (!($scope->isInClass())) {
            return false;
        }

        if (empty($nodes)) {
            return false;
        }

        foreach ($nodes as $node) {
            $class = $scope->getClassReflection();
            if ($class !== null && !(new Classname($node, false))->matches($class)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array<class-string> $nodes
     * @throws ShouldNotHappenException
     * @return array<RuleError>
     */
    protected function validateGetErrors(Scope $scope, array $nodes): array
    {
        $errors  = [];
        $subject = $scope->getClassReflection();
        if ($subject === null) {
            throw new ShouldNotHappenException();
        }

        foreach ($this->statements as [$selector, $subjectExcludes, $targets, $targetExcludes]) {
            if ($subject->isBuiltin() || !$selector->matches($subject)) {
                continue;
            }
            foreach ($subjectExcludes as $exclude) {
                if ($exclude->matches($subject)) {
                    continue 2;
                }
            }

            array_push($errors, ...$this->applyValidation($subject, $targets, $targetExcludes, $nodes));
        }

        return $errors;
    }
}
