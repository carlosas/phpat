<?php

declare(strict_types=1);

namespace PhpAT\Parser\Parser;

use function array_map;
use function count;
use DirectoryIterator;
use Exception;
use function file_get_contents;
use function file_put_contents;
use function in_array;
use function ksort;
use const PHP_EOL;
use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\NodeVisitorAbstract;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter\Standard;
use function preg_match;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RuntimeException;
use SplFileInfo;
use function sprintf;
use function str_replace;
use function strlen;
use function strtolower;
use function substr;
use function var_export;

class NodeVisitor extends NodeVisitorAbstract
{
    /** @var array<string> */
    private array $classNames = [];
    /** @var array<string> */
    private array $functionNames = [];
    /** @var array<string> */
    private array $constantNames = [];

    /**
     * {@inheritdoc}
     */
    public function enterNode(Node $node): ?int
    {
        if ($node instanceof Node\Stmt\ClassLike) {
            $this->classNames[] = $node->namespacedName->toString();

            return NodeTraverser::DONT_TRAVERSE_CHILDREN;
        }

        return null;
    }

    /**
     * @return array<string>
     */
    public function getClassNames(): array
    {
        return $this->classNames;
    }

    /**
     * @return array<string>
     */
    public function getFunctionNames(): array
    {
        return $this->functionNames;
    }

    /**
     * @return array<string>
     */
    public function getConstantNames(): array
    {
        return $this->constantNames;
    }

    public function clear(): void
    {
        $this->classNames    = [];
        $this->functionNames = [];
        $this->constantNames = [];
    }
}
