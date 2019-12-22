<?php

declare(strict_types=1);

namespace PhpAT\Rule\Type;

use PhpAT\App\Configuration;
use PHPAT\EventDispatcher\EventDispatcher;
use PhpAT\File\FileFinder;
use PhpAT\Parser\AstNode;
use PhpAT\Statement\Event\StatementNotValidEvent;
use PhpAT\Statement\Event\StatementValidEvent;
use PhpParser\NodeTraverserInterface;
use PhpParser\Parser;
use PHPStan\PhpDocParser\Parser\PhpDocParser;

class Dependency implements RuleType
{
    /**
     * @var FileFinder
     */
    private $finder;
    /**
     * @var Parser
     */
    private $parser;
    /**
     * @var NodeTraverserInterface
     */
    private $traverser;
    /**
     * @var PhpDocParser
     */
    private $docParser;
    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    public function __construct(
        FileFinder $finder,
        Parser $parser,
        NodeTraverserInterface $traverser,
        PhpDocParser $docParser,
        EventDispatcher $eventDispatcher
    ) {
        $this->finder = $finder;
        $this->parser = $parser;
        $this->traverser = $traverser;
        $this->docParser = $docParser;
        $this->eventDispatcher = $eventDispatcher;
        $this->ignoreDocBlocks = Configuration::getDependencyIgnoreDocBlocks();
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
                $result = in_array($fqcnDestination, $node->getDependencies());
                $this->dispatchResult($result, $inverse, $fqcnOrigin, $fqcnDestination);
            }
        }

        return;
    }

    private function dispatchResult(bool $result, bool $inverse, string $fqcnOrigin, string $fqcnDestination): void
    {
        $action = $result ? ' depends on ' : ' does not depend on ';
        $event = ($result xor $inverse) ? StatementValidEvent::class : StatementNotValidEvent::class;
        $message = $fqcnOrigin . $action . $fqcnDestination;

        $this->eventDispatcher->dispatch(new $event($message));
    }
}
