<?php declare(strict_types=1);

namespace PHPat;

final class Configuration
{
    private bool $ignore_doc_comments;
    private bool $ignore_built_in_classes;
    private bool $show_rule_names;

    public function __construct(
        bool $ignore_doc_comments,
        bool $ignore_built_in_classes,
        bool $show_rule_names
    ) {
        $this->ignore_doc_comments = $ignore_doc_comments;
        $this->ignore_built_in_classes = $ignore_built_in_classes;
        $this->show_rule_names = $show_rule_names;
    }

    public function ignoreDocComments(): bool
    {
        return $this->ignore_doc_comments;
    }

    public function ignoreBuiltInClasses(): bool
    {
        return $this->ignore_built_in_classes;
    }

    public function showRuleNames(): bool
    {
        return $this->show_rule_names;
    }
}
