<?php

declare(strict_types=1);

namespace PhpAT\Rule\Type\Dependency;

use PHPAT\EventDispatcher\EventDispatcher;
use PhpAT\Parser\AstNode;
use PhpAT\Rule\Type\RuleType;
use PhpAT\Statement\Event\StatementNotValidEvent;
use PhpAT\Statement\Event\StatementValidEvent;
use PHPStan\PhpDocParser\Parser\PhpDocParser;

class MustDepend implements RuleType
{
    /**
     * @var PhpDocParser
     */
    private $docParser;
    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    public function __construct(
        PhpDocParser $docParser,
        EventDispatcher $eventDispatcher
    ) {
        $this->docParser = $docParser;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function validate(
        string $fqcnOrigin,
        array $fqcnDestinations,
        array $astMap,
        bool $inverse = false
    ): void {
        /** @var AstNode $node */
        foreach ($astMap as $node) {
            if ($node->getClassName() !== $fqcnOrigin) {
                continue;
            }

            foreach ($fqcnDestinations as $destination) {
                $result = in_array($destination, $node->getDependencies());
                $this->dispatchResult($result, $inverse, $fqcnOrigin, $destination);
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
