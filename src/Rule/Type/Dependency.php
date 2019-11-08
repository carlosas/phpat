<?php

declare(strict_types=1);

namespace PhpAT\Rule\Type;

use PhpAT\File\FileFinder;
use PhpAT\Parser\ClassMatcher;
use PhpAT\Parser\ClassName;
use PhpAT\Parser\Collector\ClassNameCollector;
use PhpAT\Parser\Collector\DependencyCollector;
use PhpAT\Statement\Event\StatementNotValidEvent;
use PhpAT\Statement\Event\StatementValidEvent;
use PhpParser\NodeTraverserInterface;
use PhpParser\Parser;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class Dependency implements RuleType
{
    private $traverser;
    private $finder;
    private $parser;
    /**
     * @var ClassName
     */
    private $parsedClassClassName;
    /**
     * @var ClassName[]
     */
    private $parsedClassDependencies;
    private $eventDispatcher;

    public function __construct(
        FileFinder $finder,
        Parser $parser,
        NodeTraverserInterface $traverser,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->finder = $finder;
        $this->parser = $parser;
        $this->traverser = $traverser;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function validate(
        array $parsedClass,
        array $destinationFiles,
        bool $inverse = false
    ): void {
        $this->resetCollectedItems();

        $this->extractParsedClassInfo($parsedClass);

        $classNameCollector = new ClassNameCollector();
        $this->traverser->addVisitor($classNameCollector);

        /**
         * @var \SplFileInfo $file
        */
        foreach ($destinationFiles as $file) {
            $parsed = $this->parser->parse(file_get_contents($file->getPathname()));
            $this->traverser->traverse($parsed);
        }
        $this->traverser->removeVisitor($classNameCollector);

        //TODO: Change to FatalErrorEvent (could not find any class in the test)
        if (empty($classNameCollector->getResult())) {
            return;
        }

        /**
         * @var ClassName $className
        */
        foreach ($classNameCollector->getResult() as $className) {
            $result = empty($this->parsedClassDependencies)
                ? false
                : in_array($className->getFQDN(), $this->parsedClassDependencies);

            $this->dispatchResult($result, $inverse, $this->parsedClassClassName, $className);
        }
    }

    private function extractParsedClassInfo(array $parsedClass): void
    {
        $classNameCollector = new ClassNameCollector();
        $this->traverser->addVisitor($classNameCollector);
        $this->traverser->traverse($parsedClass);
        $this->traverser->removeVisitor($classNameCollector);
        $this->parsedClassClassName = $classNameCollector->getResult()[0];

        $matcher = new ClassMatcher();
        $matcher->saveNamespace($this->parsedClassClassName->getNamespace());

        $dependencyExtractor = new DependencyCollector($matcher);
        $this->traverser->addVisitor($dependencyExtractor);
        $this->traverser->traverse($parsedClass);
        $this->traverser->removeVisitor($dependencyExtractor);
        $this->parsedClassDependencies = $dependencyExtractor->getResult();

        /**
         * @var ClassName $v
        */
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
        $action = $result ? ' depends on ' : ' does not depend on ';
        $event = ($result xor $inverse) ? StatementValidEvent::class : StatementNotValidEvent::class;
        $message = $className->getFQDN() . $action . $dependencyName->getFQDN();

        $this->eventDispatcher->dispatch($event, new $event($message));
    }

    private function resetCollectedItems()
    {
        $this->parsedClassClassName = null;
        $this->parsedClassDependencies = null;
    }
}
