<?php declare(strict_types=1);

namespace PHPat\Statement\Builder;

use PHPat\Statement\Statement;
use PHPat\Test\Rule;
use PHPat\Test\TestParser;
use PhpParser\Node;
use PHPStan\Rules\Rule as PHPStanRule;

final class StatementBuilder
{
    /** @var array<Rule> */
    private array $rules;

    public function __construct(TestParser $testParser)
    {
        $this->rules = $testParser();
    }

    /**
     * @param  class-string<PHPStanRule<Node>> $assertion
     * @return array<Statement>
     */
    public function build(string $assertion): array
    {
        $statements = [];
        foreach ($this->rules as $rule) {
            if ($rule->getAssertion() !== $assertion) {
                continue;
            }

            $ruleName = $this->extractRuleName($rule->getRuleName());
            foreach ($rule->getSubjects() as $subject) {
                $statements[] = new Statement(
                    $ruleName,
                    $subject,
                    $rule->getSubjectExcludes(),
                    $rule->getTargets(),
                    $rule->getTargetExcludes(),
                    $rule->getTips(),
                    $rule->getParams()
                );
            }
        }

        return $statements;
    }

    private function extractRuleName(string $fullName): string
    {
        $randomName = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 8);
        $pos = strpos($fullName, ':');
        $name = substr($fullName, $pos !== false ? $pos + 1 : 0);
        $sanitized = is_string($sanitized = preg_replace_callback('/_([a-zA-Z])/', fn ($matches) => strtoupper($matches[1]), $name)) ? $sanitized : $randomName;
        $sanitized = is_string($sanitized = preg_replace('/[^a-zA-Z0-9.]/', '', $sanitized)) ? $sanitized : $randomName;
        $sanitized = is_string($sanitized = preg_replace('/\.+/', '.', $sanitized)) ? $sanitized : $randomName;

        return trim($sanitized, '.');
    }
}
