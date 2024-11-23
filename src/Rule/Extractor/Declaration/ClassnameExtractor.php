<?php declare(strict_types=1);

namespace PHPat\Rule\Extractor\Declaration;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassNode;

trait ClassnameExtractor
{
    public function getNodeType(): string
    {
        return InClassNode::class;
    }

    /**
     * @param InClassNode $node
     */
    protected function meetsDeclaration(Node $node, Scope $scope, array $params = []): bool
    {
        if (!isset($params['isRegex'], $params['classname'])) {
            return false;
        }

        $namespacedName = $node->getOriginalNode()->namespacedName;

        if (!$namespacedName instanceof Node\Name) {
            return false;
        }

        $namespacedName = $namespacedName->toString();

        if ($params['isRegex'] === true) {
            return preg_match($params['classname'], $namespacedName) === 1;
        }

        return $namespacedName === $params['classname'];
    }
}
