<?php

$dir = __DIR__ . '/src/Selector';
$files = array_merge(
    glob($dir . '/*.php'),
    glob($dir . '/Modifier/*.php')
);

foreach ($files as $file) {
    if (!is_file($file))
        continue;
    $content = file_get_contents($file);
    $original = $content;

    // Update matches parameter type
    $content = str_replace(
        'public function matches(\ReflectionClass $classReflection): bool',
        "/**\n     * @param \PHPStan\Reflection\ClassReflection \$classReflection\n     */\n    public function matches(\$classReflection): bool",
        $content
    );
    $content = preg_replace(
        '/public function matches\(\\\\?ReflectionClass \$([a-zA-Z0-9_]+)\): bool/',
        "/**\n     * @param \PHPStan\Reflection\ClassReflection $$1\n     */\n    public function matches(\$$1): bool",
        $content
    );

    // Update SelectorInterface
    if (basename($file) === 'SelectorInterface.php') {
        $content = str_replace(
            'public function matches(\ReflectionClass $classReflection): bool;',
            "/**\n     * @param \PHPStan\Reflection\ClassReflection \$classReflection\n     */\n    public function matches(\$classReflection): bool;",
            $content
        );
    }

    // AbstractSelector methods
    $content = preg_replace(
        '/protected function matches(\\\\?ReflectionClass \$[a-zA-Z0-9_]+): bool/',
        'protected function matches(\PHPStan\Reflection\ClassReflection $classReflection): bool',
        $content
    );

    if ($original !== $content) {
        file_put_contents($file, $content);
        echo "Updated $file\n";
    }
}
