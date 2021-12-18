<?php

declare(strict_types=1);

namespace PhpAT\Rule\Assertion;

class MatchResult
{
    private bool $matched;
    private array $matches;

    /**
     * MatchResult constructor.
     * @param string[] $matches
     */
    public function __construct(bool $matched, array $matches)
    {
        $this->matched = $matched;
        $this->matches = $matches;
    }

    public function matched(): bool
    {
        return $this->matched;
    }

    /**
     * @return string[]
     */
    public function getMatches(): array
    {
        return $this->matches;
    }
}
