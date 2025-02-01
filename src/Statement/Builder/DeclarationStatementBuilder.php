<?php declare(strict_types=1);

namespace PHPat\Statement\Builder;

use PHPat\Selector\SelectorInterface;
use PHPat\Test\RelationRule;
use PHPat\Test\Rule;
use PhpParser\Node;
use PHPStan\Rules\Rule as PHPStanRule;

final class DeclarationStatementBuilder implements StatementBuilder
{
    /** @var array<array{string, SelectorInterface, array<SelectorInterface>, array<string>, array<string, mixed>}> */
    private array $statements = [];

    /** @var array<RelationRule> */
    private array $rules;

    /** @var class-string<PHPStanRule<Node>> */
    private string $assertion;

    /**
     * @param class-string<PHPStanRule<Node>> $assertion
     * @param array<RelationRule>             $rules
     */
    public function __construct(string $assertion, array $rules)
    {
        $this->assertion = $assertion;
        $this->rules = $rules;
    }

    /**
     * @return array<array{string, SelectorInterface, array<SelectorInterface>, array<string>, array<string, mixed>}>
     */
    public function build(): array
    {
        $params = $this->extractCurrentAssertion($this->rules);

        foreach ($params as $param) {
            $this->addStatement($param[0], $param[1], $param[2], $param[3], $param[4]);
        }

        return $this->statements;
    }

    /**
     * @param array<SelectorInterface> $subjectExcludes
     * @param array<string>            $tips
     * @param array<string, mixed>     $params
     */
    private function addStatement(
        string $ruleName,
        SelectorInterface $subject,
        array $subjectExcludes,
        array $tips,
        array $params
    ): void {
        $this->statements[] = [$ruleName, $subject, $subjectExcludes, $tips, $params];
    }

    /**
     * @param  array<Rule>                                                                                            $rules
     * @return array<array{string, SelectorInterface, array<SelectorInterface>, array<string>, array<string, mixed>}>
     */
    private function extractCurrentAssertion(array $rules): array
    {
        $result = [];
        foreach ($rules as $rule) {
            if ($rule->getAssertion() !== $this->assertion) {
                continue;
            }

            $ruleName = $this->extractRuleName($rule->getRuleName());
            foreach ($rule->getSubjects() as $selector) {
                $result[] = [$ruleName, $selector, $rule->getSubjectExcludes(), $rule->getTips(), $rule->getParams()];
            }
        }

        return $result;
    }

    private function extractRuleName(string $fullName): string
    {
        $randomName = mb_substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 8);
        $pos = mb_strpos($fullName, ':');
        $name = mb_substr($fullName, $pos !== false ? $pos + 1 : 0);
        $sanitized = is_string($sanitized = preg_replace_callback('/_([a-zA-Z])/', fn ($matches) => mb_strtoupper($matches[1]), $name)) ? $sanitized : $randomName;
        $sanitized = is_string($sanitized = preg_replace('/[^a-zA-Z0-9.]/', '', $sanitized)) ? $sanitized : $randomName;
        $sanitized = is_string($sanitized = preg_replace('/\.+/', '.', $sanitized)) ? $sanitized : $randomName;

        return mb_trim($sanitized, '.');
    }
}
