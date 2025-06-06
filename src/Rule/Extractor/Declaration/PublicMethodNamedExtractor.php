<?php declare(strict_types=1);

namespace PHPat\Rule\Extractor\Declaration;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassNode;

trait PublicMethodNamedExtractor
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
        if (!isset($params['isRegex'], $params['name'])) {
            return false;
        }

        $reflectionClass = $node->getClassReflection()->getNativeReflection();
        $methods = $reflectionClass->getMethods(\ReflectionMethod::IS_PUBLIC);

        $methodsMeetingDeclaration = array_filter(
            $methods,
            function ($method) use ($params) {
                if ($method->getName() === '__construct') {
                    return false;
                }

                return $params['isRegex'] === true
                    ? preg_match($params['name'], $method->getName()) === 1
                    : $method->getName() === $params['name'];
            }
        );

        return count($methodsMeetingDeclaration) === 1;
    }
}
