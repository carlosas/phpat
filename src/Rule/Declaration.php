<?php declare(strict_types=1);

namespace PhpAT\Rule;

use PhpAT\Parser\DeclarationExtractor;
use PhpParser\NodeTraverser;

class Declaration implements RuleType
{
    private $traverser;

    public function __construct()
    {
        $this->traverser = new NodeTraverser();
    }

    public function validate(array $parsedClass, array $params): bool
    {
        $declarationExt = new DeclarationExtractor();
        $this->traverser->addVisitor($declarationExt);
        $this->traverser->traverse($parsedClass);

        $declarations = array_keys($declarationExt->getResult());
        foreach ($params as $param) {
            if (in_array($param, $declarations)) {
                return true;
            }
        }

        return false;
    }

    public function getMessageVerb(): string
    {
        return 'declare';
    }
}
