<?php

namespace PhpAT\Parser\Ast;

use phpDocumentor\Reflection\Types\Context;
use PHPStan\PhpDocParser\Ast\Type;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\ParserException;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\PhpDocParser\Parser\TokenIterator;

/**
 * Class PhpDocTypeResolver
 * @package PhpAT\Parser\Ast
 */
class PhpDocTypeResolver
{
    /** @var PhpDocParser */
    private $docParser;

    public function __construct(PhpDocParser $docParser)
    {
        $this->docParser = $docParser;
    }

    public function getBlockClassNames(Context $context, string $docBlock): array
    {
        try {
            $nodes = $this->docParser->parse(new TokenIterator((new Lexer())->tokenize($docBlock)));
        } catch (ParserException $e) {
            return [];
        }

        foreach ($nodes->getTags() as $tag) {
            if (isset($tag->value->type)) {
                $names = $this->resolveTypeNode($tag->value->type);
                foreach ($names as $name) {
                    $result[] = $this->resolveNameFromContext($context, $name);
                }
            }
        }

        return $result ?? [];
    }

    /**
     * @param Type\TypeNode $type
     * @return string[]
     */
    public function resolveTypeNode(Type\TypeNode $type): array
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
