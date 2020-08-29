<?php

namespace PhpAT\Parser\Ast\Type;

use phpDocumentor\Reflection\Types\Context;
use PHPStan\PhpDocParser\Ast\Type;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\PhpDocParser\Parser\TokenIterator;

class PhpDocTypeResolver
{
    /** @var PhpDocParser */
    private $docParser;
    /** @var PhpStanNodeTypeExtractor */
    private $typeExtractor;

    public function __construct(PhpDocParser $docParser, PhpStanNodeTypeExtractor $typeExtractor)
    {
        $this->docParser = $docParser;
        $this->typeExtractor = $typeExtractor;
    }

    public function getBlockClassNames(Context $context, string $docBlock): array
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
            && !PhpType::isBuiltinType($type->name)
            && !PhpType::isSpecialType($type->name)
        ) {
            return [$type->name];
        }

        if ($type instanceof Type\ArrayTypeNode || $type instanceof Type\NullableTypeNode) {
            return $this->resolveTypeNode($type->type);
        }

        if ($type instanceof Type\GenericTypeNode) {
            $types[] = $this->resolveTypeNode($type->type);
            foreach ($type->genericTypes as $innerType) {
                $types[] = $this->resolveTypeNode($innerType);
            }
            return array_merge(...$types);
        }

        return [];
    }

    private function resolveNameFromContext(Context $context, string $name): string
    {
        if (strpos($name, '\\') === 0) {
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
