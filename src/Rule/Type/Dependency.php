<?php

declare(strict_types=1);

namespace PhpAT\Rule\Type;

use PhpAT\File\FileFinder;
use PhpAT\Output\OutputInterface;
use PhpAT\Parser\ClassName;
use PhpAT\Parser\Collector\ClassNameCollector;
use PhpAT\Parser\Collector\DependencyCollector;
use PhpAT\Statement\Event\StatementNotValidEvent;
use PhpParser\NodeTraverserInterface;
use PhpParser\Parser;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class Dependency implements RuleType
{
    private $traverser;
    private $finder;
    private $parser;
    /** @var ClassName */
    private $parsedClassClassName;
    /** @var ClassName[] */
    private $parsedClassDependencies;
    private $eventDispatcher;
    /**
     * @var OutputInterface
     */
    private $output;

    public function __construct(
        FileFinder $finder,
        Parser $parser,
        NodeTraverserInterface $traverser,
        EventDispatcherInterface $eventDispatcher,
        OutputInterface $output
    ) {
        $this->finder = $finder;
        $this->parser = $parser;
        $this->traverser = $traverser;
        $this->eventDispatcher = $eventDispatcher;
        $this->output = $output;
    }

    public function validate(
        array $parsedClass,
        array $destinationFiles,
        array $destinationExcludedFiles,
        bool $inverse = false
    ): void {
        $this->extractParsedClassInfo($parsedClass);

        $filesFound = [];
        foreach ($destinationFiles as $file) {
            $found = $this->finder->findFiles($file, $destinationExcludedFiles);
            foreach ($found as $f) {
                $filesFound[] = $f;
            }
        }

        $classNameCollector = new ClassNameCollector();
        $this->traverser->addVisitor($classNameCollector);

        /** @var \SplFileInfo $file */
        foreach ($filesFound as $file) {
            $parsed = $this->parser->parse(file_get_contents($file->getPathname()));
            $this->traverser->traverse($parsed);
        }
        $this->traverser->removeVisitor($classNameCollector);

        //TODO: Change to FatalErrorEvent (could not find any class in the test)
        if (empty($classNameCollector->getResult())) {
            return;
        }

        /** @var ClassName $className */
        foreach ($classNameCollector->getResult() as $className) {
            $result = empty($this->parsedClassDependencies)
                ? false
                : in_array($className->getFQDN(), $this->parsedClassDependencies);

            $this->dispatchResult($result, $inverse, $this->parsedClassClassName, $className);
        }
    }

    public function getMessageVerb(): string
    {
        return 'depend on';
    }

    private function extractParsedClassInfo(array $parsedClass): void
    {
        $dependencyExtractor = new DependencyCollector();
        $classNameExtractor = new ClassNameCollector();

        $this->traverser->addVisitor($dependencyExtractor);
        $this->traverser->addVisitor($classNameExtractor);
        $this->traverser->traverse($parsedClass);
        $this->traverser->removeVisitor($dependencyExtractor);

        $this->parsedClassClassName = $classNameExtractor->getResult()[0];
        $this->parsedClassDependencies = $dependencyExtractor->getResult();

        /** @var ClassName $v */
        foreach ($this->parsedClassDependencies as $k => $v) {
            if (empty($v->getNamespace())) {
                $className = new ClassName($this->parsedClassClassName->getNamespace(), $v->getName());
                $this->parsedClassDependencies[$k] = $className->getFQDN();
            } else {
                $this->parsedClassDependencies[$k] = $v->getFQDN();
            }
        }
    }

    private function dispatchResult(bool $result, bool $inverse, ClassName $className, ClassName $dependencyName): void
    {
        if (false === ($result xor $inverse)) {
            $error = $inverse ? ' depends on ' : ' does not depend on ';
            $message = $className->getFQDN() . $error . $dependencyName->getFQDN();
            $this->eventDispatcher->dispatch(StatementNotValidEvent::class, new StatementNotValidEvent($message));
        } else {
            $this->output->write('-');
        }
    }
}
