<?php declare(strict_types=1);

namespace PhpAT\Rule\Type;

interface RuleType
{
    public function validate(array $parsedClass, array $params): bool;

    public function getMessageVerb(): string;
}
