<?php

declare(strict_types=1);

namespace PhpAT\Test;

use PhpAT\App\Configuration;
use PHPAT\EventDispatcher\EventDispatcher;
use PhpAT\Rule\RuleBuilder;
use PhpAT\Test\Parser\XmlTestParser;
use PhpAT\Test\Parser\YamlTestParser;
use Psr\EventDispatcher\EventDispatcherInterface;

class FileTestExtractor implements TestExtractor
{
    private RuleBuilder $ruleBuilder;
    private string $testPath;
    private EventDispatcherInterface $eventDispatcher;
    private YamlTestParser $yamlTestParser;
    private XmlTestParser $xmlTestParser;

    public function __construct(
        RuleBuilder $ruleBuilder,
        EventDispatcherInterface $eventDispatcher,
        YamlTestParser $yamlTestParser,
        XmlTestParser $xmlTestParser,
        Configuration $configuration
    ) {
        $this->ruleBuilder     = $ruleBuilder;
        $this->eventDispatcher = $eventDispatcher;
        $this->yamlTestParser  = $yamlTestParser;
        $this->xmlTestParser   = $xmlTestParser;
        $this->testPath        = getcwd() . '/' . $configuration->getTestsPath();
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
            if (preg_match('/^[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*(\.php)$/', $file)) {
                include $this->testPath . '/' . $file;
            }
            if (preg_match('/^[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*((\.yaml)|(\.yml))$/', $file)) {
                $classes[] = $this->yamlTestParser->parseFile($this->testPath . $file);
            }
            if (preg_match('/^[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*(\.xml)$/', $file)) {
                $classes[] = $this->xmlTestParser->parseFile($this->testPath . $file);
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
