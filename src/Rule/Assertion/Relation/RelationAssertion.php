<?php declare(strict_types=1);

namespace PHPat\Rule\Assertion\Relation;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Assertion;
use PHPat\Rule\Assertion\Relation\ShouldApplyAttribute\ShouldApplyAttribute;
use PHPat\Rule\Assertion\Relation\ShouldExtend\ShouldExtend;
use PHPat\Rule\Assertion\Relation\ShouldImplement\ShouldImplement;
use PHPat\Rule\Assertion\Relation\ShouldInclude\ShouldInclude;
use PHPat\Selector\Classname;
use PHPat\Selector\SelectorInterface;
use PHPat\ShouldNotHappenException;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\RuleError;
use PHPStan\Type\FileTypeMapper;

abstract class RelationAssertion implements Assertion
{
    /** @var array<array{string, SelectorInterface, array<SelectorInterface>, array<SelectorInterface>, array<SelectorInterface>, array<string>}> */
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
        $nodes = $this->extractNodeClassNames($node, $scope);

        if (!$this->ruleApplies($scope, $nodes)) {
            return [];
        }

        return $this->validateGetErrors($scope, $nodes);
    }

    public function prepareMessage(string $ruleName, string $message): string
    {
        return $this->configuration->showRuleNames()
            ? sprintf('%s: %s', $ruleName, $message)
            : $message;
    }

    /**
     * @return array<class-string>
     */
    abstract protected function extractNodeClassNames(Node $node, Scope $scope): array;

    /**
     * @param class-string $subject
     */
    abstract protected function getMessage(string $ruleName, string $subject, string $target): string;

    /**
     * @param  array<SelectorInterface> $targets
     * @param  array<SelectorInterface> $targetExcludes
     * @param  array<class-string>      $nodes
     * @param  array<string>            $tips
     * @return array<RuleError>
     */
    abstract protected function applyValidation(
        string $ruleName,
        ClassReflection $subject,
        array $targets,
        array $targetExcludes,
        array $nodes,
        array $tips
    ): array;

    /**
     * @param array<class-string> $nodes
     */
    protected function ruleApplies(Scope $scope, array $nodes): bool
    {
        if (!$scope->isInClass()) {
            return false;
        }

        // Can not skip if the rule is a ShouldExtend, ShouldImplement, ShouldInclude or ShouldApplyAttribute rule
        if (is_a($this, ShouldExtend::class) || is_a($this, ShouldImplement::class) || is_a($this, ShouldInclude::class) || is_a($this, ShouldApplyAttribute::class)) {
            return true;
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
     * @param  array<class-string>      $nodes
     * @return array<RuleError>
     * @throws ShouldNotHappenException
     */
    protected function validateGetErrors(Scope $scope, array $nodes): array
    {
        $errors = [];
        $subject = $scope->getClassReflection();
        if ($subject === null) {
            throw new ShouldNotHappenException();
        }

        foreach ($this->statements as [$ruleName, $selector, $subjectExcludes, $targets, $targetExcludes, $tips]) {
            if ($subject->isBuiltin() || !$selector->matches($subject)) {
                continue;
            }
            foreach ($subjectExcludes as $exclude) {
                if ($exclude->matches($subject)) {
                    continue 2;
                }
            }

            if ($this->configuration->ignoreBuiltInClasses() === true) {
                $nodes = $this->removeBuiltInClasses($nodes);
            }

            array_push(
                $errors,
                ...$this->applyValidation($ruleName, $subject, $targets, $targetExcludes, $nodes, $tips)
            );
        }

        return $errors;
    }

    /**
     * @param  array<class-string> $nodes
     * @return array<class-string>
     */
    private function removeBuiltInClasses(array $nodes): array
    {
        return array_filter(
            $nodes,
            fn (string $node): bool => !$this->isBuiltInClass($node)
        );
    }

    private function isBuiltInClass(string $node): bool
    {
        return $node === 'Stringable'
            || ($this->reflectionProvider->hasClass($node) && $this->reflectionProvider->getClass($node)->isBuiltin());
    }
}
