<?php

require __DIR__ . '/../src/Parser/BuiltInClasses.php';

use PHPat\Parser\BuiltInClasses;

$stubsDir = $argv[1] ?? __DIR__ . '/../vendor/jetbrains/phpstorm-stubs';

if (!is_dir($stubsDir)) {
    echo "Stubs directory not found: $stubsDir\n";
    exit(1);
}

$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($stubsDir)
);

$stubClasses = [];

foreach ($files as $file) {
    if ($file->getExtension() !== 'php')
        continue;

    $content = file_get_contents($file->getPathname());
    $namespace = '';

    if (preg_match('/^\s*namespace\s+([^;]+);/m', $content, $matches)) {
        $namespace = $matches[1] . '\\';
    }

    if (preg_match_all('/^\s*(?:final\s+)?(?:abstract\s+)?(?:class|interface|trait|enum)\s+(\w+)/m', $content, $matches)) {
        foreach ($matches[1] as $className) {
            $stubClasses[] = $namespace . $className;
        }
    }
}

$builtInClasses = BuiltInClasses::PHP_BUILT_IN_CLASSES;

$missingClasses = array_diff($stubClasses, $builtInClasses);

sort($missingClasses);
$missingClasses = array_unique($missingClasses);

if (count($missingClasses) > 0) {
    echo "The following classes from phpstorm-stubs are missing in PHPat\Parser\BuiltInClasses:\n";
    foreach ($missingClasses as $class) {
        echo "- $class\n";
    }
    exit(1);
}

echo "All classes from phpstorm-stubs are present in PHPat\Parser\BuiltInClasses.\n";
exit(0);