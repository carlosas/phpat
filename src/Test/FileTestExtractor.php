<?php declare(strict_types=1);

namespace PHPArchiTest\Test;

class FileTestExtractor implements TestExtractor
{
    private $testPath;

    public function __construct(string $testPath)
    {
        $this->testPath = $testPath;
    }

    public function execute(): ArchiTestCollection
    {
        $tests = new ArchiTestCollection();

        $testClasses = $this->getTestClasses();
        foreach ($testClasses as $class) {
            $tests->addValue(new $class);
        }

        return $tests;
    }

    private function getTestClasses(): array
    {
        $files = scandir($this->testPath);
        if (!$files) {
            return [];
        }

        foreach ($files as $file) {
            if (preg_match('/^([.A-Za-z\/])+(\.php)$/', $file)) {
                include $this->testPath.'/'.$file;
            }
        }

        $classes = [];
        foreach (get_declared_classes() as $declaredClass) {
            if (get_parent_class($declaredClass) == ArchiTest::class) {
                $classes[] = $declaredClass;
            }
        }

        return $classes;
    }
}
