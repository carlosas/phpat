<?php

declare(strict_types=1);

namespace PhpAT\File;

use Symfony\Component\Finder\Iterator\MultiplePcreFilterIterator;

class PathnameFilterIterator extends MultiplePcreFilterIterator
{
    public function accept(): bool
    {
        $filename = $this->current()->getPathname();

        if ('\\' === \DIRECTORY_SEPARATOR) {
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
        return false !== strpos($str, '*');
    }

    protected function parseRegex(string $str)
    {
        $substrs = explode('*', $str);
        if (1 === count($substrs)) {
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
}
