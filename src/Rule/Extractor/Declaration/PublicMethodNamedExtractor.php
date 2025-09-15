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
        $publicMethods = $reflectionClass->getMethods(\ReflectionMethod::IS_PUBLIC);

        $publicMethodsWithoutConstructor = array_filter(
            $publicMethods,
            fn ($method) => $method->getName() !== '__construct'
        );

        if (count($publicMethodsWithoutConstructor) !== 1) {
            return false;
        }

        $singleMethod = reset($publicMethodsWithoutConstructor);

        return $params['isRegex'] === true
            ? preg_match($params['name'], $singleMethod->getName()) === 1
            : $singleMethod->getName() === $params['name'];
    }
}
