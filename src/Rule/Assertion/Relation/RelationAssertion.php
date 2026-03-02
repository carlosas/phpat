<?php declare(strict_types=1);

namespace PHPat\Rule\Assertion\Relation;

use PHPat\Configuration;
use PHPat\Parser\BuiltInClasses;
use PHPat\Rule\Assertion\Assertion;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Selector\Classname;
use PHPat\Selector\SelectorInterface;
use PHPat\ShouldNotHappenException;
use PHPat\Statement\Statement;
use PHPat\Statement\StatementBuilder;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Type\FileTypeMapper;

abstract class RelationAssertion implements Assertion
{
    /** @var array<Statement> */
    protected array $statements;
    protected Configuration $configuration;
    protected ReflectionProvider $reflectionProvider;
    protected FileTypeMapper $fileTypeMapper;

    public function __construct(
        string $assertionType,
        StatementBuilder $statementBuilder,
        Configuration $configuration,
        ReflectionProvider $reflectionProvider,
        FileTypeMapper $fileTypeMapper
    ) {
        $this->statements = $statementBuilder->build($assertionType);
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

    abstract protected function getRelationVerb(): string;

    /**
     * @param array<class-string> $nodes
     */
    protected function ruleApplies(Scope $scope, array $nodes): bool
    {
        if (!$scope->isInClass()) {
            return false;
        }

        foreach ($this->statements as $statement) {
            if ($statement->constraint === Constraint::Should) {
                return true;
            }
        }

        if (empty($nodes)) {
            return false;
        }

        foreach ($nodes as $node) {
            $class = $scope->getClassReflection();

            /** @var \ReflectionClass<object> $nativeReflection */
            $nativeReflection = $class->getNativeReflection();
            if (!(new Classname($node, false))->matches($nativeReflection)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  array<class-string>       $nodes
     * @return list<IdentifierRuleError>
     * @throws ShouldNotHappenException
     */
    protected function validateGetErrors(Scope $scope, array $nodes): array
    {
        $errors = [];
        $subject = $scope->getClassReflection();
        if ($subject === null) {
            throw new ShouldNotHappenException();
        }

        foreach ($this->statements as $statement) {
            /** @var \ReflectionClass<object> $nativeReflection */
            $nativeReflection = $subject->getNativeReflection();
            if ($subject->isBuiltin() || !$statement->subject->matches($nativeReflection)) {
                continue;
            }
            foreach ($statement->subjectExcludes as $exclude) {
                /** @var \ReflectionClass<object> $nativeReflection */
                $nativeReflection = $subject->getNativeReflection();
                if ($exclude->matches($nativeReflection)) {
                    continue 2;
                }
            }

            if ($this->configuration->ignoreBuiltInClasses() === true) {
                $nodes = $this->removeBuiltInClasses($nodes);
            }

            $validationErrors = match ($statement->constraint) {
                Constraint::Should => $this->applyShould(
                    $statement->ruleName,
                    $subject,
                    $statement->targets,
                    $statement->targetExcludes,
                    $nodes,
                    $statement->tips,
                    $statement->constraint
                ),
                Constraint::ShouldNot => $this->applyShouldNot(
                    $statement->ruleName,
                    $subject,
                    $statement->targets,
                    $statement->targetExcludes,
                    $nodes,
                    $statement->tips,
                    $statement->constraint
                ),
                Constraint::CanOnly => $this->applyCanOnly(
                    $statement->ruleName,
                    $subject,
                    $statement->targets,
                    $statement->targetExcludes,
                    $nodes,
                    $statement->tips,
                    $statement->constraint
                ),
            };

            array_push($errors, ...$validationErrors);
        }

        return $errors;
    }

    protected function getMessage(string $ruleName, string $subject, string $target, Constraint $constraint): string
    {
        $negation = ($constraint === Constraint::Should) ? '' : ' not';

        return $this->prepareMessage(
            $ruleName,
            sprintf('%s should%s %s %s', $subject, $negation, $this->getRelationVerb(), $target)
        );
    }

    /**
     * @param  array<SelectorInterface>  $targets
     * @param  array<SelectorInterface>  $targetExcludes
     * @param  array<class-string>       $nodes
     * @param  array<string>             $tips
     * @return list<IdentifierRuleError>
     * @throws ShouldNotHappenException
     */
    private function applyShould(string $ruleName, ClassReflection $subject, array $targets, array $targetExcludes, array $nodes, array $tips, Constraint $constraint): array
    {
        $errors = [];
        foreach ($targets as $target) {
            $targetFound = false;
            foreach ($nodes as $node) {
                if ($this->nodeMatchesTarget($node, $target, $targetExcludes)) {
                    $targetFound = true;

                    break;
                }
            }
            if (!$targetFound) {
                $ruleError = RuleErrorBuilder::message($this->getMessage($ruleName, $subject->getName(), $target->getName(), $constraint));
                foreach ($tips as $tip) {
                    $ruleError->addTip($tip);
                }
                $errors[] = $ruleError->identifier('phpat.'.$ruleName)->build();
            }
        }

        return $errors;
    }

    /**
     * @param  array<SelectorInterface>  $targets
     * @param  array<SelectorInterface>  $targetExcludes
     * @param  array<class-string>       $nodes
     * @param  array<string>             $tips
     * @return list<IdentifierRuleError>
     * @throws ShouldNotHappenException
     */
    private function applyShouldNot(string $ruleName, ClassReflection $subject, array $targets, array $targetExcludes, array $nodes, array $tips, Constraint $constraint): array
    {
        $errors = [];
        foreach ($targets as $target) {
            foreach ($nodes as $node) {
                if ($this->nodeMatchesTarget($node, $target, $targetExcludes)) {
                    $ruleError = RuleErrorBuilder::message($this->getMessage($ruleName, $subject->getName(), $node, $constraint));
                    foreach ($tips as $tip) {
                        $ruleError->addTip($tip);
                    }
                    $errors[] = $ruleError->identifier('phpat.'.$ruleName)->build();
                }
            }
        }

        return $errors;
    }

    /**
     * @param  array<SelectorInterface>  $targets
     * @param  array<SelectorInterface>  $targetExcludes
     * @param  array<class-string>       $nodes
     * @param  array<string>             $tips
     * @return list<IdentifierRuleError>
     * @throws ShouldNotHappenException
     */
    private function applyCanOnly(string $ruleName, ClassReflection $subject, array $targets, array $targetExcludes, array $nodes, array $tips, Constraint $constraint): array
    {
        $errors = [];
        foreach ($nodes as $node) {
            foreach ($targets as $target) {
                if ($this->nodeMatchesTarget($node, $target, $targetExcludes)) {
                    continue 2;
                }
            }
            $ruleError = RuleErrorBuilder::message($this->getMessage($ruleName, $subject->getName(), $node, $constraint));
            foreach ($tips as $tip) {
                $ruleError->addTip($tip);
            }
            $errors[] = $ruleError->identifier('phpat.'.$ruleName)->build();
        }

        return $errors;
    }

    /**
     * @param class-string             $classname
     * @param array<SelectorInterface> $targetExcludes
     */
    private function nodeMatchesTarget(string $classname, SelectorInterface $target, array $targetExcludes): bool
    {
        if (!$this->reflectionProvider->hasClass($classname)) {
            return false;
        }

        $class = $this->reflectionProvider->getClass($classname);

        /** @var \ReflectionClass<object> $nativeReflection */
        $nativeReflection = $class->getNativeReflection();
        if (!$target->matches($nativeReflection)) {
            return false;
        }

        foreach ($targetExcludes as $exclude) {
            /** @var \ReflectionClass<object> $nativeReflection */
            $nativeReflection = $class->getNativeReflection();
            if ($exclude->matches($nativeReflection)) {
                return false;
            }
        }

        return true;
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
        return in_array($node, BuiltInClasses::PHP_BUILT_IN_CLASSES, true)
            || ($this->reflectionProvider->hasClass($node) && $this->reflectionProvider->getClass($node)->isBuiltin());
    }
}
