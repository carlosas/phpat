<?php

declare(strict_types=1);

namespace PhpAT;

use RuntimeException;
use Phar;
use SplFileInfo;
use Generator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class Compiler
{
    /**
     * @var string[]
     */
    const EXCLUDED_FILES = ['phpat', 'build.php', 'src/Compiler.php'];

    /**
     * @var string
     */
    const PHPUNIT_PATTERN = '/^\/?vendor\/phpunit\/?(.*)?/';

    /**
     * @var string[]
     */
    protected $paths;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $file;

    function __construct(array $paths, string $name = 'phpat.phar')
    {
        $this->name = $name;
        $this->paths = $paths;
        $this->file = $this->paths['output'] . DIRECTORY_SEPARATOR . $name;
    }

    public function run(): void
    {
        $this->cleanOutputDir();

        $phar = new Phar($this->file, 0, $this->name);

        $phar->startBuffering();

        $this->addSrcFilesToPhar($phar);
        $this->addVendorFilesToPhar($phar);
        $this->addBinaryToPhar($phar);

        $stub = $this->getStub();
        $phar->setStub($stub);

        $phar->stopBuffering();
    }

    protected function cleanOutputDir(): void
    {
        if (file_exists($this->file) && unlink($this->file) === false) {
            throw new RuntimeException("Failed to delete {$this->file}");
        }
    }

    protected function getRelativePath(string $path): string
    {
        return str_replace($this->paths['base'] . DIRECTORY_SEPARATOR, '', $path);
    }

    protected function getFilesFrom(string $path): Generator
    {
        $files = new RecursiveDirectoryIterator($path);
        $iterator = new RecursiveIteratorIterator($files);

        foreach ($iterator as $path => $file) {
            yield $this->getRelativePath($path) => $file;
        }
    }

    protected function addSrcFilesToPhar(Phar $phar): void
    {
        foreach ($this->getFilesFrom($this->paths['src']) as $path => $file) {
            if (in_array($file, self::EXCLUDED_FILES, true) || $file->isDir()) {
                continue;
            }

            $this->addFileToPhar($phar, $path, $file);
        }
    }

    protected function addVendorFilesToPhar(Phar $phar): void
    {
        foreach ($this->getFilesFrom($this->paths['vendor']) as $path => $file) {
            if (preg_match(self::PHPUNIT_PATTERN, $path) || $file->isDir()) {
                continue;
            }

            $this->addFileToPhar($phar, $path, $file);
        }
    }

    protected function addFileToPhar(Phar $phar, string $path, SplFileInfo $file): void
    {
        if (! $file->isReadable()) {
            throw new RuntimeException("Failed to read $path and couldn't add it to the archive");
        }

        $size = $file->getSize();

        if ($size === 0) {
            return;
        }

        $handle = $file->openFile();
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
