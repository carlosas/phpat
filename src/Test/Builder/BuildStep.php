<?php declare(strict_types=1);

namespace PHPat\Test\Builder;

class BuildStep extends AbstractStep
{
    public function nonIgnorable(): static
    {
        $this->rule->nonIgnorable = true;

        return $this;
    }
}
