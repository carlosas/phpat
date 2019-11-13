<?php declare(strict_types=1);

namespace PhpAT\Test;

use PhpAT\Rule\RuleBuilder;

class FileTestExtractor implements TestExtractor
{
    /** @var RuleBuilder */
    private $ruleBuilder;

    /** @var string */
    private $testPath;

    public function __construct(RuleBuilder $ruleBuilder, string $testPath)
    {
        $this->ruleBuilder = $ruleBuilder;
        $this->testPath = $testPath;
    }

    public function execute(): ArchitectureTestCollection
    {
        $tests = new ArchitectureTestCollection();

        $testClasses = $this->getTestClasses();
        foreach ($testClasses as $class) {
            $tests->addValue(new $class($this->ruleBuilder));
        }

        return $tests;
    }

    /** @return string[] */
    private function getTestClasses(): array
    {
        $files = scandir($this->testPath);
        if (!$files) {
            return [];
        }

        foreach ($files as $file) {
            if (preg_match('/^([.A-Za-z\/])+(\.php)$/', $file)) {
                include $this->testPath . '/' . $file;
            }
        }

        $classes = [];
        foreach (get_declared_classes() as $declaredClass) {
            if (get_parent_class($declaredClass) == ArchitectureTest::class) {
                $classes[] = $declaredClass;
            }
        }

        return $classes;
    }
}
