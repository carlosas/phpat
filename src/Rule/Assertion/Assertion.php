<?php

declare(strict_types=1);

namespace PHPat\Rule\Assertion;

use PHPat\Selector\Classname;
use PHPat\Selector\SelectorInterface;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\Rule as PHPStanRule;
use PHPStan\Rules\RuleError;
use PHPStan\ShouldNotHappenException;
use PHPStan\Type\FileTypeMapper;

abstract class Assertion implements PHPStanRule
{
    /** @var array<array{SelectorInterface, array<SelectorInterface>}> */
    protected array $statements;
    protected ReflectionProvider $reflectionProvider;
    protected FileTypeMapper $fileTypeMapper;

    /**
     * @param class-string<Assertion> $assertion
     */
    public function __construct(
        string $assertion,
        StatementBuilderFactory $statementBuilderFactory,
        ReflectionProvider $reflectionProvider,
        FileTypeMapper $fileTypeMapper
    ) {
        $this->statements         = $statementBuilderFactory->create($assertion)->build();
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
     * @return iterable<class-string>
     */
    abstract protected function extractNodeClassNames(Node $node, Scope $scope): iterable;

    /**
     * @param class-string $subject
     * @param class-string $target
     */
    abstract protected function getMessage(string $subject, string $target): string;

    /**
     * @param array<SelectorInterface> $targets
     * @param array<class-string> $nodes
     * @return array<RuleError>
     */
    abstract protected function applyValidation(ClassReflection $subject, array $targets, array $nodes): array;

    /**
     * @param iterable<class-string> $nodes
     */
    protected function ruleApplies(Scope $scope, iterable $nodes): bool
    {
        if (!($scope->isInClass())) {
            return false;
        }

        if (empty($nodes)) {
            return true;
        }

        foreach ($nodes as $node) {
            if (!(new Classname($node))->matches($scope->getClassReflection())) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param iterable<class-string> $nodes
     * @throws ShouldNotHappenException
     * @return array<RuleError>
     */
    protected function validateGetErrors(Scope $scope, iterable $nodes): array
    {
        $subject = $scope->getClassReflection();
        $errors  = [];

        foreach ($this->statements as [$selector, $targets]) {
            if ($subject->isBuiltin() || !$selector->matches($subject)) {
                continue;
            }

            array_push($errors, ...$this->applyValidation($subject, $targets, $nodes));
        }

        return $errors;
    }
}
