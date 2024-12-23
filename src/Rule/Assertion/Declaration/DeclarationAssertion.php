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
        $subject = $scope->getClassReflection();
        if ($subject === null) {
            return [];
        }

        $applicableStatements = array_filter(
            $this->statements,
            static function (array $statement) use ($subject): bool {
                [$ruleName, $selector, $subjectExcludes, $tips, $params] = $statement;

                if ($subject->isBuiltin() || !$selector->matches($subject)) {
                    return false;
                }
                foreach ($subjectExcludes as $exclude) {
                    if ($exclude->matches($subject)) {
                        return false;
                    }
                }

                return true;
            }
        );

        return array_values(array_reduce(
            $applicableStatements,
            function (array $errors, array $statement) use ($node, $scope, $subject): array {
                [$ruleName, $selector, $subjectExcludes, $tips, $params] = $statement;

                $meetsDeclaration = $this->meetsDeclaration($node, $scope, $statement[4]);
                array_push($errors, ...$this->applyValidation($ruleName, $subject, $meetsDeclaration, $tips, $params));

                return $errors;
            },
            []
        ));
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
    abstract protected function applyValidation(string $ruleName, ClassReflection $subject, bool $meetsDeclaration, array $tips, array $params = []): array;
}
