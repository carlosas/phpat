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

        $pos = mb_strrpos($node->getClassReflection()->getName(), '\\');
        $classname = $pos === false
            ? $node->getClassReflection()->getName()
            : mb_substr($node->getClassReflection()->getName(), $pos + 1);

        if ($params['isRegex'] === true) {
            return preg_match($params['classname'], $classname) === 1;
        }

        return $classname === $params['classname'];
    }
}
