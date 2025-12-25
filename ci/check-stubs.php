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
    if ($file->getExtension() !== 'php') {
        continue;
    }

    $content = file_get_contents($file->getPathname());
    $tokens = token_get_all($content);

    $namespace = '';
    $count = count($tokens);

    for ($i = 0; $i < $count; $i++) {
        $token = $tokens[$i];

        if (is_array($token)) {
            if ($token[0] === T_NAMESPACE) {
                // Find namespace name
                $i++;
                // Skip whitespace
                while ($i < $count && is_array($tokens[$i]) && $tokens[$i][0] === T_WHITESPACE) {
                    $i++;
                }

                $currentNamespace = '';
                // Read namespace parts (T_STRING, T_NS_SEPARATOR, T_NAME_QUALIFIED)
                while ($i < $count) {
                    if (is_string($tokens[$i]) && ($tokens[$i] === ';' || $tokens[$i] === '{')) {
                        // End of namespace declaration
                        break;
                    }
                    if (is_array($tokens[$i]) && ($tokens[$i][0] === T_STRING || $tokens[$i][0] === T_NS_SEPARATOR || (defined('T_NAME_QUALIFIED') && $tokens[$i][0] === T_NAME_QUALIFIED))) {
                        $currentNamespace .= $tokens[$i][1];
                    }
                    $i++;
                }
                $namespace = $currentNamespace;
            } elseif (in_array($token[0], [T_CLASS, T_INTERFACE, T_TRAIT, T_ENUM])) {
                // Check if it is not "double colon class" like ClassName::class
                $j = $i + 1;
                while ($j < $count && is_array($tokens[$j]) && $tokens[$j][0] === T_WHITESPACE) {
                    $j++;
                }

                if ($j < $count && is_array($tokens[$j]) && $tokens[$j][0] === T_STRING) {
                    $className = $tokens[$j][1];

                    // Verify it's not a look-aliike or anonymous class
                    $k = $i - 1;
                    while ($k >= 0 && is_array($tokens[$k]) && $tokens[$k][0] === T_WHITESPACE) {
                        $k--;
                    }
                    if ($k >= 0 && is_array($tokens[$k]) && $tokens[$k][0] === T_NEW) {
                        continue;
                    }

                    // Also check for ::class (e.g. static::class, self::class, or SomeClass::class)
                    // But in stubs, T_CLASS is usually defining a class.
                    // ::class usage usually comes AFTER a string or static/self keywork, not as a start.
                    // But token_get_all emits T_CLASS for ::class usage too.
                    // If the PREVIOUS non-whitespace token was :: (T_DOUBLE_COLON), we skip.
                    if ($k >= 0 && is_string($tokens[$k]) && $tokens[$k] === ':') {
                        // token_get_all might parse :: as two colons or T_DOUBLE_COLON depending on PHP version?
                        // Actually T_DOUBLE_COLON is standard.
                        // Let's check safely.
                        // But ::class usually: T_STRING T_DOUBLE_COLON T_CLASS
                    }
                    if ($k >= 0 && is_array($tokens[$k]) && $tokens[$k][0] === T_DOUBLE_COLON) {
                        continue;
                    }

                    $fullClassName = $namespace ? $namespace . '\\' . $className : $className;
                    $stubClasses[] = $fullClassName;
                }
            }
        }
    }
}

$builtInClasses = BuiltInClasses::PHP_BUILT_IN_CLASSES;

// Filter and check for missing classes
$missingClasses = array_diff($stubClasses, $builtInClasses);

sort($missingClasses);
$missingClasses = array_unique($missingClasses);

// Filter out weird parsing artifacts if any
$missingClasses = array_filter($missingClasses, fn($c) => !empty($c) && !str_contains($c, "class@anonymous"));

if (count($missingClasses) > 0) {
    echo "The following classes from phpstorm-stubs are missing in PHPat\Parser\BuiltInClasses:\n";
    foreach ($missingClasses as $class) {
        echo "\"$class\",\n";
    }
    exit(1);
}

echo "All classes from phpstorm-stubs are present in PHPat\Parser\BuiltInClasses.\n";
exit(0);