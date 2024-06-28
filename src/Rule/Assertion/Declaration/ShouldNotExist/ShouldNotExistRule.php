<?php declare(strict_types=1);

namespace PHPat\Rule\Assertion\Declaration\ShouldNotExist;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<InClassNode>
 */
final class ShouldNotExistRule extends ShouldNotExist implements Rule
{
    protected function getMessage(string $ruleName, string $subject, array $params = []): string
    {
        return $this->prepareMessage(
            $ruleName,
            sprintf('%s should not exist', $subject)
        );
    }

    protected function meetsDeclaration(Node $node, Scope $scope, array $params = []): bool
    {
        return true;
    }
}
