<?php

declare(strict_types=1);

namespace PhpAT\Rule\Assertion;

use PhpAT\Parser\Ast\SrcNode;
use PhpAT\Parser\Ast\ReferenceMap;
use PhpAT\Parser\ClassLike;
use PhpAT\Parser\Relation\Composition;
use PhpAT\Parser\Relation\Dependency;
use PhpAT\Parser\Relation\Inheritance;
use PhpAT\Parser\Relation\Mixin;
use Psr\EventDispatcher\EventDispatcherInterface;

abstract class AbstractAssertion
{
    /** @var EventDispatcherInterface */
    protected $eventDispatcher;

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
    protected function getDependencies(SrcNode $node): array
    {
        foreach ($node->getRelations() as $relation) {
            if ($relation instanceof Dependency) {
                $dependencies[] = $relation->relatedClass->getFQCN();
            }
        }

        return $dependencies ?? [];
    }

    /**
     * @return string[]
     */
    protected function getInterfaces(SrcNode $node): array
    {
        foreach ($node->getRelations() as $relation) {
            if ($relation instanceof Composition) {
                $interfaces[] = $relation->relatedClass->getFQCN();
            }
        }

        return $interfaces ?? [];
    }

    protected function getParent(SrcNode $node): ?string
    {
        foreach ($node->getRelations() as $relation) {
            if ($relation instanceof Inheritance) {
                return $relation->relatedClass->getFQCN();
            }
        }

        return null;
    }

    /**
     * @return string[]
     */
    protected function getTraits(SrcNode $node): array
    {
        foreach ($node->getRelations() as $relation) {
            if ($relation instanceof Mixin) {
                $mixins[] = $relation->relatedClass->getFQCN();
            }
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
}
