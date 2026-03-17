<?php declare(strict_types=1);

namespace Tests\PHPat\unit;

trait CreatesPhpFile
{
    /** @var list<string> */
    private array $tempFiles = [];

    protected function tearDown(): void
    {
        parent::tearDown();
        foreach ($this->tempFiles as $file) {
            @unlink($file);
        }
    }

    protected function createPhpFile(string $code): string
    {
        $base = tempnam(sys_get_temp_dir(), 'phpat_');
        if ($base === false) {
            throw new \RuntimeException('Failed to create temporary file via tempnam()');
        }
        $file = $base.'.php';
        if (file_put_contents($file, $code) === false) {
            throw new \RuntimeException(sprintf('Failed to write PHP code to temporary file "%s"', $file));
        }
        @unlink($base);
        $this->tempFiles[] = $file;

        // Load classes so PHPStan's ReflectionProvider can resolve them
        if (preg_match('/namespace\s+([^;]+);/', $code, $ns)
            && preg_match('/(?:^|\n)\s*(?:abstract\s+|final\s+|readonly\s+)*(?:class|interface|trait|enum)\s+(\w+)/m', $code, $cl)
        ) {
            $fqcn = $ns[1].'\\'.$cl[1];
            if (!class_exists($fqcn, false)
                && !interface_exists($fqcn, false)
                && !trait_exists($fqcn, false)
                && !enum_exists($fqcn, false)
            ) {
                require $file;
            }
        }

        return $file;
    }
}
