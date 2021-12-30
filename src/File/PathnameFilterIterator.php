<?php

declare(strict_types=1);

namespace PhpAT\File;

class PathnameFilterIterator extends \FilterIterator
{
    /** @var array<string> */
    protected array $excluded = [];

    public function __construct(\Iterator $iterator, array $noMatchPatterns)
    {
        foreach ($noMatchPatterns as $pattern) {
            $this->excluded[] = $this->toRegex($pattern);
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
        foreach ($this->excluded as $regex) {
            if (preg_match($regex, $string)) {
                return false;
            }
        }

        return true;
    }
}
