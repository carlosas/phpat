<?php

declare(strict_types=1);

namespace PhpAT\Rule\Assertion;

use PhpAT\App\Configuration;
use PHPAT\EventDispatcher\EventDispatcher;
use PhpAT\Parser\Ast\SrcNode;
use PhpAT\Parser\Ast\ReferenceMap;
use PhpAT\Parser\ClassLike;
use PhpAT\Parser\Relation\Composition;
use PhpAT\Parser\Relation\Dependency;
use PhpAT\Parser\Relation\Inheritance;
use PhpAT\Parser\Relation\Mixin;

abstract class AbstractAssertion
{
    /** @var Configuration */
    protected $configuration;
    /** @var EventDispatcher */
    protected $eventDispatcher;

    public function __construct(EventDispatcher $eventDispatcher, Configuration $configuration)
    {
        $this->configuration = $configuration;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param ClassLike    $origin
     * @param ClassLike[]  $destinations
     * @param ClassLike[]  $excluded
     * @param ReferenceMap $map
     */
    abstract public function validate(ClassLike $origin, array $destinations, array $excluded, ReferenceMap $map): void;

    abstract public function acceptsRegex(): bool;

    /**
     * @param ClassLike    $origin
     * @param ReferenceMap $map
     * @return SrcNode[]
     */
    protected function filterMatchingNodes(ClassLike $origin, ReferenceMap $map): array
    {
        foreach ($map->getSrcNodes() as $node) {
            if ($origin->matches($node->getClassName())) {
                $found[] = $node;
            }
        }

        return $found ?? [];
    }

    /**
     * @return string[]
     */
    protected function getDependencies(SrcNode $node, ReferenceMap $map): array
    {
        foreach ($node->getRelations() as $relation) {
            if (
                !($relation instanceof Dependency)
                || $this->isIgnored($relation->relatedClass, $map)
            ) {
                continue;
            }

            $dependencies[] = $relation->relatedClass->getFQCN();
        }

        return $dependencies ?? [];
    }

    /**
     * @return string[]
     */
    protected function getInterfaces(SrcNode $node, ReferenceMap $map): array
    {
        foreach ($node->getRelations() as $relation) {
            if (
                !($relation instanceof Composition)
                || $this->isIgnored($relation->relatedClass, $map)
            ) {
                continue;
            }

            $interfaces[] = $relation->relatedClass->getFQCN();
        }

        return $interfaces ?? [];
    }

    protected function getParent(SrcNode $node, ReferenceMap $map): ?string
    {
        foreach ($node->getRelations() as $relation) {
            if (
                !($relation instanceof Inheritance)
                || $this->isIgnored($relation->relatedClass, $map)
            ) {
                continue;
            }

            return $relation->relatedClass->getFQCN();
        }

        return null;
    }

    /**
     * @return string[]
     */
    protected function getTraits(SrcNode $node, ReferenceMap $map): array
    {
        foreach ($node->getRelations() as $relation) {
            if (
                !($relation instanceof Mixin)
                || $this->isIgnored($relation->relatedClass, $map)
            ) {
                continue;
            }

            $mixins[] = $relation->relatedClass->getFQCN();
        }

        return $mixins ?? [];
    }

    /**
     * @param string      $relation
     * @param ClassLike[] $destinations
     * @param ClassLike[] $excluded
     * @return MatchResult
     */
    protected function relationMatchesDestinations(string $relation, array $destinations, array $excluded): MatchResult
    {
        foreach ($excluded as $exc) {
            if ($exc->matches($relation)) {
                return new MatchResult(false, []);
            }
        }

        foreach ($destinations as $des) {
            if ($des->matches($relation)) {
                $m[] = $des->toString();
            }
        }

        return new MatchResult(!empty($m), $m ?? []);
    }

    /**
     * @param ClassLike   $destination
     * @param ClassLike[] $excluded
     * @param string[] $relations
     * @return MatchResult
     */
    protected function destinationMatchesRelations(
        ClassLike $destination,
        array $excluded,
        array $relations
    ): MatchResult {
        foreach ($excluded as $exc) {
            if ($exc->matches($destination->toString()) || $destination->matches($exc->toString())) {
                return new MatchResult(false, []);
            }
        }

        foreach ($relations as $rel) {
            if ($destination->matches($rel)) {
                $m[] = $rel;
            }
        }

        return new MatchResult(!empty($m), $m ?? []);
    }

    protected function isIgnored(ClassLike $class, ReferenceMap $map): bool
    {
        if ($this->configuration->getIgnorePhpExtensions() === false) {
            return false;
        }

        /** @var ClassLike $extensionClass */
        foreach ($map->getExtensionNodes() as $extensionClass) {
            if ($extensionClass->matches($class->toString())) {
                return true;
            }
        }

        return false;
    }
}
