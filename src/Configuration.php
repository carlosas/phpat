<?php

declare(strict_types=1);

namespace PHPat;

class Configuration
{
    private bool $ignore_doc_comments;

    public function __construct(
        bool $ignore_doc_comments
    ) {
        $this->ignore_doc_comments = $ignore_doc_comments;
    }

    public function ignoreDocComments(): bool
    {
        return $this->ignore_doc_comments;
    }
}
