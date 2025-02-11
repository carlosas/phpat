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

        $methodsWithoutConstructor = array_filter(
            $methods,
            fn($method) => $method->getName() !== '__construct'
        );

        foreach ($methodsWithoutConstructor as $method) {
            if ($params['isRegex'] === true) {
                if(1 !== preg_match($params['name'], $method->getName())) {
                    return false;
                }

                continue;
            }

            if ($method->getName() !== $params['name']) {
                return false;
            }
        }

        return true;
    }
}
