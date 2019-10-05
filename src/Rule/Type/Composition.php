<?php

declare(strict_types=1);

namespace PhpAT\Rule\Type;

use PhpAT\File\FileFinder;
use PhpAT\Output\OutputInterface;
use PhpAT\Parser\ClassName;
use PhpAT\Parser\Collector\ClassNameCollector;
use PhpAT\Parser\Collector\InterfaceCollector;
use PhpAT\Statement\Event\NoClassesFoundEvent;
use PhpAT\Statement\Event\StatementNotValidEvent;
use PhpAT\Statement\Event\StatementValidEvent;
use PhpParser\NodeTraverserInterface;
use PhpParser\Parser;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class Composition implements RuleType
{
    private $traverser;
    private $finder;
    private $parser;
    /** @var ClassName */
    private $parsedClassClassName;
    /** @var ClassName[] */
    private $parsedClassInterfaces;
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
        bool $inverse = false
    ): void {
        $this->extractParsedClassInfo($parsedClass);

        $classNameCollector = new ClassNameCollector();
        $this->traverser->addVisitor($classNameCollector);
        /** @var \SplFileInfo $file */
        foreach ($destinationFiles as $file) {
            $parsed = $this->parser->parse(file_get_contents($file->getPathname()));
            $this->traverser->traverse($parsed);
        }
        $this->traverser->removeVisitor($classNameCollector);

        if (empty($classNameCollector->getResult())) {
            $this->eventDispatcher->dispatch(NoClassesFoundEvent::class, new NoClassesFoundEvent());
            return;
        }

        /** @var ClassName $className */
        foreach ($classNameCollector->getResult() as $className) {
            $result = empty($this->parsedClassInterfaces)
                ? false
                : in_array($className->getFQDN(), $this->parsedClassInterfaces);

            $this->dispatchResult($result, $inverse, $this->parsedClassClassName, $className);
        }
    }

    private function extractParsedClassInfo(array $parsedClass): void
    {
        $interfaceCollector = new InterfaceCollector();
        $classNameCollector = new ClassNameCollector();

        $this->traverser->addVisitor($interfaceCollector);
        $this->traverser->addVisitor($classNameCollector);
        $this->traverser->traverse($parsedClass);
        $this->traverser->removeVisitor($interfaceCollector);

        $this->parsedClassClassName = $classNameCollector->getResult()[0];
        $this->parsedClassInterfaces = $interfaceCollector->getResult();

        /** @var ClassName $v */
        foreach ($this->parsedClassInterfaces as $k => $v) {
            if (empty($v->getNamespace())) {
                $className = new ClassName($this->parsedClassClassName->getNamespace(), $v->getName());
                $this->parsedClassInterfaces[$k] = $className->getFQDN();
            } else {
                $this->parsedClassInterfaces[$k] = $v->getFQDN();
            }
        }
    }

    private function dispatchResult(bool $result, bool $inverse, ClassName $className, ClassName $interfaceName): void
    {
        $action = ($result or $inverse) ? ' implements ' : ' does not implement ';
        $event = ($result xor $inverse) ? StatementValidEvent::class : StatementNotValidEvent::class;
        $message = $className->getFQDN() . $action . $interfaceName->getFQDN();

        $this->eventDispatcher->dispatch($event, new $event($message));
    }
}
