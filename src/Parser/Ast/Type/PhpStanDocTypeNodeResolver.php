<?php

namespace PhpAT\Parser\Ast\Type;

use PhpAT\Parser\Ast\ClassContext;
use PHPStan\PhpDocParser\Ast\Type;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\PhpDocParser\Parser\TokenIterator;

class PhpStanDocTypeNodeResolver
{
    /** @var PhpDocParser */
    private $docParser;
    /** @var PhpStanDocNodeTypeExtractor */
    private $typeExtractor;

    public function __construct(PhpDocParser $docParser, PhpStanDocNodeTypeExtractor $typeExtractor)
    {
        $this->docParser = $docParser;
        $this->typeExtractor = $typeExtractor;
    }

    /**
     * @param ClassContext $context
     * @param string  $docBlock
     * @return string[]
     */
    public function getBlockClassNames(ClassContext $context, string $docBlock): array
    {
        try {
            $nodes = $this->docParser->parse(new TokenIterator((new Lexer())->tokenize($docBlock)));
        } catch (\Throwable $e) {
            return [];
        }

        foreach ($nodes->getTags() as $tag) {
            $types = $this->typeExtractor->getTypesNodes($tag);
            foreach ($types as $type) {
                if ($type !== null) {
                    $names = $this->resolveTypeNode($type);
                    foreach ($names as $name) {
                        $result[] = $this->resolveNameFromContext($context, $name);
                    }
                }
            }
        }

        return $result ?? [];
    }

    /**
     * @param Type\TypeNode $type
     * @return string[]
     */
    private function resolveTypeNode(Type\TypeNode $type): array
    {
        if (
            $type instanceof Type\IdentifierTypeNode
        ) {
            return [$type->name];
        }

        if ($type instanceof Type\ArrayTypeNode || $type instanceof Type\NullableTypeNode) {
            return $this->resolveTypeNode($type->type);
        }

        if ($type instanceof Type\UnionTypeNode) {
            foreach ($type->types as $t) {
                $typesUnion = array_merge($typesUnion ?? [], $this->resolveTypeNode($t));
            }
            return $typesUnion ?? [];
        }

        if ($type instanceof Type\GenericTypeNode) {
            $typesGeneric[] = $this->resolveTypeNode($type->type);
            foreach ($type->genericTypes as $innerType) {
                $typesGeneric[] = $this->resolveTypeNode($innerType);
            }
            return array_merge(...$typesGeneric);
        }

        if ($type instanceof Type\CallableTypeNode) {
            $typesCallable[] = $this->resolveTypeNode($type->returnType);
            foreach ($type->parameters as $parameter) {
                $typesCallable[] = $this->resolveTypeNode($parameter->type);
            }
            return array_merge(...$typesCallable);
        }

        return [];
    }

    private function resolveNameFromContext(ClassContext $context, string $name): string
    {
        if (
            strpos($name, '\\') === 0
            || PhpType::isBuiltinType($name)
            || PhpType::isSpecialType($name)
        ) {
            return $name;
        }

        $parts = explode('\\', $name);
        $link = $parts[0] ?? [];
        if (isset($context->getNamespaceAliases()[$link])) {
            array_shift($parts);
            if (empty($parts)) {
                return $context->getNamespaceAliases()[$link];
            }

            return $context->getNamespaceAliases()[$link] . '\\' . implode('\\', $parts);
        }

        return $context->getNamespace() . '\\' . implode('\\', $parts);
    }
}
