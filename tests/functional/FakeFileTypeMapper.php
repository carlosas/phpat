<?php

declare(strict_types=1);

namespace Tests\PHPat\functional;

use PHPStan\PhpDoc\ResolvedPhpDocBlock;
use PHPStan\Type\FileTypeMapper;
use ReflectionClass;

class FakeFileTypeMapper extends FileTypeMapper
{
    public function getResolvedPhpDoc(string $fileName, ?string $className, ?string $traitName, ?string $functionName, string $docComment) : ResolvedPhpDocBlock
    {
        return ResolvedPhpDocBlock::createEmpty();
    }

    public static function create(): self
    {
        /** @var self $self */
        $self = (new ReflectionClass(self::class))->newInstanceWithoutConstructor();

        return $self;
    }
}
