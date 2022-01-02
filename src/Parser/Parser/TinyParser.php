<?php

declare(strict_types=1);

namespace PhpAT\Parser\Parser;

use DirectoryIterator;
use Exception;
use function file_get_contents;
use function in_array;
use const PHP_EOL;
use PhpAT\Parser\Ast\FullClassName;
use PhpAT\Parser\Ast\SrcNode;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\Parser;
use PhpParser\ParserFactory;
use function preg_match;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;
use function sprintf;

class TinyParser
{
    private Parser $parser;

    public function __construct()
    {
        $this->parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
    }

    public function parseDir(string $dir): array
    {
        $parsed = [];
        foreach (new DirectoryIterator($dir) as $directoryInfo) {
            /** @var DirectoryIterator $directoryInfo */
            if ($directoryInfo->isDot()) {
                continue;
            }
            if (!$directoryInfo->isDir()) {
                continue;
            }
            if (in_array($directoryInfo->getBasename(), ['tests', 'meta', 'vendor', 'couchbase_v2'], true)) {
                continue;
            }

            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($directoryInfo->getPathName(), RecursiveDirectoryIterator::SKIP_DOTS)
            );

            foreach ($iterator as $fileInfo) {
                array_push($parsed, $this->parse($fileInfo));
            }
        }

        return $parsed;
    }

    /**
     * @throws Exception
     * @return array<SrcNode>
     */
    public function parse(\SplFileInfo $fileInfo): array
    {
        if (!$this->isReadablePhpFile($fileInfo->getPathname())) {
            return [];
        }

        $fileVisitor   = new NodeVisitor();
        $nodeTraverser = new NodeTraverser();
        $nodeTraverser->addVisitor(new NameResolver());
        $nodeTraverser->addVisitor($fileVisitor);

        $ast = $this->parser->parse(file_get_contents($fileInfo->getPathname()));
        $nodeTraverser->traverse($ast);

        $map = [];
        foreach ($fileVisitor->getClassNames() as $className) {
            $map[$className] = new SrcNode($fileInfo->getPathname(), new FullClassName($className), []);
        }

        $fileVisitor->clear();

        return $map;
    }

    private function isReadablePhpFile(string $filename): bool
    {
        if (
            empty($filename)
            || !file_exists($filename)
            || !is_readable($filename)
            || !is_file($filename)
            || !preg_match('/\.php$/', $filename)
        ) {
            return false;
        }

        return true;
    }
}
