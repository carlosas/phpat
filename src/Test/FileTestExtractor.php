<?php

declare(strict_types=1);

namespace PhpAT\Test;

use PhpAT\App\Configuration;
use PhpAT\App\EventDispatcher;
use PhpAT\Rule\RuleBuilder;

class FileTestExtractor implements TestExtractor
{
    private $ruleBuilder;
    private $testPath;
    private $eventDispatcher;

    public function __construct(RuleBuilder $ruleBuilder, EventDispatcher $eventDispatcher)
    {
        $this->ruleBuilder = $ruleBuilder;
        $this->eventDispatcher = $eventDispatcher;
        $this->testPath = getcwd() . '/' . Configuration::getTestsPath();
    }

    public function execute(): ArchitectureTestCollection
    {
        $tests = new ArchitectureTestCollection();

        $testClasses = $this->getTestClasses();
        foreach ($testClasses as $class) {
            $tests->addValue(new $class($this->ruleBuilder, $this->eventDispatcher));
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
