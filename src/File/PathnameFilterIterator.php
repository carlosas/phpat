<?php declare(strict_types=1);

namespace PHPArchiTest\File;

use Symfony\Component\Finder\Iterator\PathFilterIterator;

class PathnameFilterIterator extends PathFilterIterator
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
        return $this->isRegex($str) ? $str : str_replace('\*', '(.)*', ('/'.preg_quote($str, '/').'/'));
    }

    protected function isRegex($str): bool
    {
        return false !== strpos($str, '*');
    }
}
