<?php

declare(strict_types=1);

namespace PhpAT\Rule\Type;

use PHPAT\EventDispatcher\EventDispatcher;
use PhpAT\File\FileFinder;
use PhpAT\Parser\AstNode;
use PhpAT\Parser\ClassMatcher;
use PhpAT\Parser\ClassName;
use PhpAT\Parser\Collector\ClassNameCollector;
use PhpAT\Parser\Collector\TraitCollector;
use PhpAT\Statement\Event\NoClassesFoundEvent;
use PhpAT\Statement\Event\StatementNotValidEvent;
use PhpAT\Statement\Event\StatementValidEvent;
use PhpParser\NodeTraverserInterface;
use PhpParser\Parser;

class Mixin implements RuleType
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
    private $parsedClassTraits;
    private $eventDispatcher;

    public function __construct(
        FileFinder $finder,
        Parser $parser,
        NodeTraverserInterface $traverser,
        EventDispatcher $eventDispatcher
    ) {
        $this->finder = $finder;
        $this->parser = $parser;
        $this->traverser = $traverser;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function validate(
        string $fqcnOrigin,
        string $fqcnDestination,
        array $astMap,
        bool $inverse = false
    ): void {
        /** @var AstNode $node */
        foreach ($astMap as $node) {
            if ($node->getClassName() === $fqcnOrigin) {
                $result = in_array($fqcnDestination, $node->getMixins());
                $this->dispatchResult($result, $inverse, $fqcnOrigin, $fqcnDestination);
            }
        }

        return;
    }

    private function dispatchResult(bool $result, bool $inverse, string $fqcnOrigin, string $fqcnDestination): void
    {
        $action = $result ? ' includes ' : ' does not include ';
        $event = ($result xor $inverse) ? StatementValidEvent::class : StatementNotValidEvent::class;
        $message = $fqcnOrigin . $action . $fqcnDestination;

        $this->eventDispatcher->dispatch(new $event($message));
    }
}
