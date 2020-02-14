<?php

declare(strict_types=1);

namespace PhpAT\Rule\Assertion;

use PhpAT\App\Event\WarningEvent;
use PhpAT\Parser\AstNode;
use PhpAT\Parser\ClassLike;
use PhpAT\Parser\FullClassName;
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
     * @param ClassLike   $origin
     * @param ClassLike[] $destinations
     * @param array       $astMap
     * @param bool        $inverse
     */
    public abstract function validate(ClassLike $origin, array $destinations, array $astMap): void;

    public abstract function acceptsRegex(): bool;

    /**
     * @param ClassLike $origin
     * @param array     $astMap
     * @return AstNode[]
     */
    protected function filterMatchingNodes(ClassLike $origin, array $astMap): array
    {
        /** @var AstNode $node */
        foreach ($astMap as $node) {
            if ($origin->matches($node->getClassName())) {
                $found[] = $node;
            }
        }
        return $found ?? [];
    }

    /**
     * @return string[]
     */
    protected function getDependencies(AstNode $node): array
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
    protected function getInterfaces(AstNode $node): array
    {
        foreach ($node->getRelations() as $relation) {
            if ($relation instanceof Composition) {
                $interfaces[] = $relation->relatedClass->getFQCN();
            }
        }

        return $interfaces ?? [];
    }

    protected function getParent(AstNode $node): ?string
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
    protected function getTraits(AstNode $node): array
    {
        foreach ($node->getRelations() as $relation) {
            if ($relation instanceof Mixin) {
                $mixins[] = $relation->relatedClass->getFQCN();
            }
        }

        return $mixins ?? [];
    }
}
