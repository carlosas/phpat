<?php

declare(strict_types=1);

namespace PhpAT\Test;

use PhpAT\App\Configuration;
use PHPAT\EventDispatcher\EventDispatcher;
use PhpAT\Rule\RuleBuilder;
use PhpAT\Test\Parser\YamlTestParser;

class FileTestExtractor implements TestExtractor
{
    private $ruleBuilder;
    private $testPath;
    private $eventDispatcher;
    private $yamlTestParser;

    public function __construct(RuleBuilder $ruleBuilder, EventDispatcher $eventDispatcher, YamlTestParser $yamlTestParser)
    {
        $this->ruleBuilder = $ruleBuilder;
        $this->eventDispatcher = $eventDispatcher;
        $this->yamlTestParser = $yamlTestParser;
        $this->testPath = getcwd() . '/' . Configuration::getTestsPath();
    }

    public function execute(): ArchitectureTestCollection
    {
        $tests = new ArchitectureTestCollection();

        $testClasses = $this->getTestClasses();

        foreach ($testClasses as $class) {
            $tests->addValue($class);
        }

        return $tests;
    }

    private function getTestClasses(): array
    {
        $files = scandir($this->testPath);
        if (!$files) {
            return [];
        }
        $classes = [];

        foreach ($files as $file) {
            if (preg_match('/^([.A-Za-z\/])+(\.php)$/', $file)) {
                include $this->testPath . '/' . $file;
            }
            if (preg_match('/^([.A-Za-z\/])+(\.yaml)$/', $file)) {
                $classes[] = $this->yamlTestParser->parseFile($this->testPath . $file);
            }
        }

        foreach (get_declared_classes() as $declaredClass) {
            if (get_parent_class($declaredClass) == ArchitectureTest::class) {
                $classes[] = new $declaredClass($this->ruleBuilder, $this->eventDispatcher);
            }
        }

        return $classes;
    }
}
