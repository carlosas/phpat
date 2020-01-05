<?php

require_once __DIR__ . '/vendor/autoload.php';

$compiler = new PhpAT\Compiler([
    'base'   => __DIR__,
    'src'    => __DIR__ . DIRECTORY_SEPARATOR . 'src',
    'vendor' => __DIR__ . DIRECTORY_SEPARATOR . 'vendor',
    'binary' => __DIR__ . DIRECTORY_SEPARATOR . 'phpat',
    'output' => __DIR__ . DIRECTORY_SEPARATOR . 'dist',
]);

try {
    $compiler->run();
} catch (Throwable $e) {
    fwrite(STDERR, '[ERROR] ' . $e->getMessage() . PHP_EOL);

    exit(1);
}
