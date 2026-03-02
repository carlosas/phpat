<?php declare(strict_types=1);

namespace PHPat\Rule\Assertion\Declaration;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Assertion;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Statement\Statement;
use PHPat\Statement\StatementBuilder;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Type\FileTypeMapper;

abstract class DeclarationAssertion implements Assertion
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

    public function processNode(Node $node, Scope $scope): array
    {
        $subject = $scope->getClassReflection();
        if ($subject === null) {
            return [];
        }

        $applicableStatements = array_filter(
            $this->statements,
            static function (Statement $statement) use ($subject): bool {
                /** @var \ReflectionClass<object> $nativeReflection */
                $nativeReflection = $subject->getNativeReflection();
                if ($subject->isBuiltin() || !$statement->subject->matches($nativeReflection)) {
                    return false;
                }
                foreach ($statement->subjectExcludes as $exclude) {
                    /** @var \ReflectionClass<object> $nativeReflection */
                    $nativeReflection = $subject->getNativeReflection();
                    if ($exclude->matches($nativeReflection)) {
                        return false;
                    }
                }

                return true;
            }
        );

        return array_values(array_reduce(
            $applicableStatements,
            function (array $errors, Statement $statement) use ($node, $scope, $subject): array {
                $meetsDeclaration = $this->meetsDeclaration($node, $scope, $statement->params);

                $shouldError = match ($statement->constraint) {
                    Constraint::Should => !$meetsDeclaration,
                    Constraint::ShouldNot => $meetsDeclaration,
                    default => false,
                };

                if ($shouldError) {
                    $message = $this->getMessage($statement->ruleName, $subject->getName(), $statement->constraint, $statement->params);
                    $ruleError = RuleErrorBuilder::message($message);
                    foreach ($statement->tips as $tip) {
                        $ruleError->addTip($tip);
                    }
                    $errors[] = $ruleError->identifier('phpat.'.$statement->ruleName)->build();
                }

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

    /**
     * @param array<string, mixed> $params
     */
    abstract protected function meetsDeclaration(Node $node, Scope $scope, array $params = []): bool;

    /**
     * @param array<string, mixed> $params
     */
    abstract protected function getMessage(string $ruleName, string $subject, Constraint $constraint, array $params = []): string;
}
