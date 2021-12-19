<?php

namespace PhpAT\Parser\Ast;

interface ClassLike
{
    public function matches(string $name): bool;

    /**
     * @param array<SrcNode> $nodes
     * @return array<SrcNode>
     */
    public function getMatchingNodes(array $nodes): array;

    public function toString(): string;
}
