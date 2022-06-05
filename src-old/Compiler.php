<?php

declare(strict_types=1);

namespace PHPatOld;

use Generator;
use Phar;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RuntimeException;
use SplFileInfo;

class Compiler
{
    /**
     * A list of relative file paths that should be excluded from the Phar
     *
     * @var array<string>
     */
    protected const EXCLUDED_FILES = [
        'bin/phpat',
        'build.php',
        'src/Compiler.php'
    ];

    /**
     * A list of patterns that will cause a matched file path to be excluded
     *
     * @var array<string>
     */
    protected const EXCLUDED_PATTERNS = [
        '/^\/?vendor\/phpunit\/?(.*)?/'
    ];

    /** @var array<string> */
    protected array $paths;
    protected string $name;
    protected string $file;

    public function __construct(array $paths, string $name = 'phpat.phar')
    {
        $this->name  = $name;
        $this->paths = $paths;
        $this->file  = $this->paths['output'] . DIRECTORY_SEPARATOR . $name;
    }

    public function run(): void
    {
        $this->cleanOutputDir();

        $phar = new Phar($this->file, 0, $this->name);

        $phar->startBuffering();

        $this->iterateDirectory($phar, $this->paths['src']);
        $this->iterateDirectory($phar, $this->paths['vendor']);
        $this->addBinaryToPhar($phar);

        $stub = $this->getStub();
        $phar->setStub($stub);

        $phar->stopBuffering();
    }

    protected function cleanOutputDir(): void
    {
        if (file_exists($this->file) && !unlink($this->file)) {
            throw new RuntimeException("Failed to delete {$this->file}");
        }
    }

    protected function getRelativePath(string $path): string
    {
        return str_replace($this->paths['base'] . DIRECTORY_SEPARATOR, '', $path);
    }

    protected function getFilesFrom(string $path): Generator
    {
        $files    = new RecursiveDirectoryIterator($path);
        $iterator = new RecursiveIteratorIterator($files);

        foreach ($iterator as $path => $file) {
            yield $this->getRelativePath($path) => $file;
        }
    }

    protected function fileIsValid(string $path, SplFileInfo $file): bool
    {
        if (! $file->isDir() && ! in_array($path, self::EXCLUDED_FILES, true)) {
            $isValid = true;

            foreach (self::EXCLUDED_PATTERNS as $pattern) {
                if (preg_match($pattern, $path)) {
                    $isValid = false;
                    break;
                }
            }

            return $isValid;
        }

        return false;
    }

    protected function iterateDirectory(Phar $phar, string $directory): void
    {
        $iterator = $this->getFilesFrom($directory);

        foreach ($iterator as $path => $file) {
            if (! $this->fileIsValid($path, $file)) {
                continue;
            }

            $this->addFileToPhar($phar, $path, $file);
        }
    }

    protected function addFileToPhar(Phar $phar, string $path, SplFileInfo $file): void
    {
        if (! $file->isReadable()) {
            throw new RuntimeException("Failed to read ${path} and couldn't add it to the archive");
        }

        $size = $file->getSize();

        if ($size === 0) {
            return;
        }

        $handle   = $file->openFile();
        $contents = $handle->fread($size);

        $phar->addFromString($path, $contents);
    }

    protected function addBinaryToPhar(Phar $phar): void
    {
        $bin = file_get_contents($this->paths['binary']);

        $bin = str_replace("#!/usr/bin/env php\n", '', $bin);

        $phar->addFromString('phpat', $bin);
    }

    protected function getStub(): string
    {
        return <<<STUB
#!/usr/bin/env php
<?php
Phar::mapPhar('phpat.phar');
require 'phar://phpat.phar/phpat';
__HALT_COMPILER();
STUB;
    }
}
