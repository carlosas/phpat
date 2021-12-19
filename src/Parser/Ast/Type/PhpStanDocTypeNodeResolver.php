<?php

namespace PhpAT\Parser\Ast\Type;

use PhpAT\PhpStubsMap\PhpStubsMap;
use PhpParser\NameContext;
use PhpParser\Node\Name;
use PHPStan\PhpDocParser\Ast\Type;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\PhpDocParser\Parser\TokenIterator;

class PhpStanDocTypeNodeResolver
{
    private \PHPStan\PhpDocParser\Parser\PhpDocParser $docParser;
    private \PhpAT\Parser\Ast\Type\PhpStanDocNodeTypeExtractor $typeExtractor;

    public function __construct(PhpDocParser $docParser, PhpStanDocNodeTypeExtractor $typeExtractor)
    {
        $this->docParser = $docParser;
        $this->typeExtractor = $typeExtractor;
    }

    /**
     * @return array<string>
     */
    public function getBlockClassNames(NameContext $context, string $docBlock): array
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
     * @return array<string>
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

    private function resolveNameFromContext(NameContext $context, string $name): string
    {
        $isFullyQualified = substr($name, 0, 1) === '\\';
        $name = $isFullyQualified ? substr($name, 1) : $name;

        if (
            PhpType::isCoreType($name)
            || PhpType::isBuiltinType($name)
            || PhpType::isSpecialType($name)
        ) {
            return $name;
        }

        return $isFullyQualified ? $name : $context->getResolvedClassName(new Name($name))->toString();
    }
}
