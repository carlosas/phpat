<?php

declare(strict_types=1);

namespace PhpAT\File;

class PathnameFilterIterator extends \FilterIterator
{
    protected array $matchRegexps   = [];
    protected array $noMatchRegexps = [];

    public function __construct(\Iterator $iterator, array $matchPatterns, array $noMatchPatterns)
    {
        foreach ($matchPatterns as $pattern) {
            $this->matchRegexps[] = $this->toRegex($pattern);
        }

        foreach ($noMatchPatterns as $pattern) {
            $this->noMatchRegexps[] = $this->toRegex($pattern);
        }

        parent::__construct($iterator);
    }

    public function accept(): bool
    {
        $filename = $this->current()->getPathname();

        if (\DIRECTORY_SEPARATOR === '\\') {
            $filename = str_replace('\\', '/', $filename);
        }

        return $this->isAccepted($filename);
    }

    protected function toRegex($str)
    {
        $r = $this->isRegex($str) ? $this->parseRegex($str) : preg_quote($str, '/');

        return '/' . $r . '/';
    }

    protected function isRegex($str): bool
    {
        return strpos($str, '*') !== false;
    }

    protected function parseRegex(string $str)
    {
        $substrs = explode('*', $str);
        if (count($substrs) === 1) {
            return $str;
        }

        $result = '';
        foreach ($substrs as $k => $s) {
            $s = str_replace('/', '\/', $s);
            if ($k === 0) {
                $result .= '(' . $s . ')';
                continue;
            }

            $result .= '(\w)*(' . $s . ')';
        }

        return $result;
    }

    protected function isAccepted($string)
    {
        foreach ($this->noMatchRegexps as $regex) {
            if (preg_match($regex, $string)) {
                return false;
            }
        }

        if ($this->matchRegexps) {
            foreach ($this->matchRegexps as $regex) {
                if (preg_match($regex, $string)) {
                    return true;
                }
            }

            return false;
        }

        return true;
    }
}
