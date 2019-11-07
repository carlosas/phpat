<?php

declare(strict_types=1);

namespace PhpAT\Rule\Type;

use PhpAT\File\FileFinder;
use PhpAT\Parser\ClassMatcher;
use PhpAT\Parser\ClassName;
use PhpAT\Parser\Collector\ClassNameCollector;
use PhpAT\Parser\Collector\ParentCollector;
use PhpAT\Statement\Event\StatementNotValidEvent;
use PhpAT\Statement\Event\StatementValidEvent;
use PhpParser\NodeTraverserInterface;
use PhpParser\Parser;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class Inheritance implements RuleType
{
    private $traverser;
    private $finder;
    private $parser;
    private $eventDispatcher;
    /** @var ClassName */
    private $parsedClassClassName;
    /** @var ClassName */
    private $parsedClassParent;

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
        /** @var \SplFileInfo $file */
        foreach ($destinationFiles as $file) {
            $parsedFile = $this->parser->parse(file_get_contents($file->getPathname()));
            $this->traverser->traverse($parsedFile);
        }
        $this->traverser->removeVisitor($classNameCollector);

        //TODO: Change to FatalErrorEvent (could not find any class in the test)
        if (empty($classNameCollector->getResult())) {
            return;
        }

        /** @var ClassName $className */
        foreach ($classNameCollector->getResult() as $className) {
            $result = (
                !is_null($this->parsedClassParent)
                && $className->getFQDN() === $this->parsedClassParent->getFQDN()
            );

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

        $parentCollector = new ParentCollector($matcher);
        $this->traverser->addVisitor($parentCollector);
        $this->traverser->traverse($parsedClass);
        $this->traverser->removeVisitor($parentCollector);

        if (!empty($parentCollector->getResult())) {
            $this->parsedClassParent = $parentCollector->getResult()[0];

            if (empty($this->parsedClassParent->getNamespace())) {
                $this->parsedClassParent = new ClassName(
                    $this->parsedClassClassName->getNamespace(),
                    $this->parsedClassParent->getName()
                );
            }
        }
    }

    private function dispatchResult(bool $result, bool $inverse, ClassName $className, ClassName $parentName): void
    {
        $action = $result ? ' extends ' : ' does not extend ';
        $event = ($result xor $inverse) ? StatementValidEvent::class : StatementNotValidEvent::class;
        $message = $className->getFQDN() . $action . $parentName->getFQDN();

        $this->eventDispatcher->dispatch($event, new $event($message));
    }

    private function resetCollectedItems()
    {
        $this->parsedClassClassName = null;
        $this->parsedClassParent = null;
    }
}
