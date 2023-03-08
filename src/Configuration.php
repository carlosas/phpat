<?php

declare(strict_types=1);

namespace PHPat;

final class Configuration
{
    /**
     * available variables: `{subject}`, `{relation}`, `{target}`, `{ruleName}`
     */
    private const RELATION_MESSAGE_DEFAULT_FORMAT        = '{subject} {relation} {target}';
    private const RELATION_MESSAGE_WITH_RULE_NAME_FORMAT = '{ruleName}: {subject} {relation} {target}';

    /**
     * available variables: `{subject}`, `{declaration}`, `{ruleName}`
     */
    private const DECLARATION_MESSAGE_DEFAULT_FORMAT        = '{subject} {declaration}';
    private const DECLARATION_MESSAGE_WITH_RULE_NAME_FORMAT = '{ruleName}: {subject} {declaration}';
    private bool $ignore_doc_comments;
    private bool $show_rule_name;


    public function __construct(
        bool $ignore_doc_comments,
        bool $show_rule_name
    ) {
        $this->ignore_doc_comments = $ignore_doc_comments;
        $this->show_rule_name      = $show_rule_name;
    }

    public function ignoreDocComments(): bool
    {
        return $this->ignore_doc_comments;
    }

    public function getRelationMessageFormat(): string
    {
        return $this->show_rule_name ? self::RELATION_MESSAGE_WITH_RULE_NAME_FORMAT : self::RELATION_MESSAGE_DEFAULT_FORMAT;
    }

    public function getDeclarationMessageFormat(): string
    {
        return $this->show_rule_name ? self::DECLARATION_MESSAGE_WITH_RULE_NAME_FORMAT : self::DECLARATION_MESSAGE_DEFAULT_FORMAT;
    }
}
