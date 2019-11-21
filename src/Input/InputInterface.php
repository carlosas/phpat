<?php

declare(strict_types=1);

namespace PhpAT\Input;

interface InputInterface
{
    public function getArgument(string $name, $default = null): ?string;

    public function getOptions(): array;
}
