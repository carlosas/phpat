<?php

namespace Tests\PhpAT\unit\Selector;

use PhpAT\Parser\Ast\ClassLike;
use PhpAT\Parser\Ast\ComposerPackage;
use PhpAT\Parser\Ast\SrcNode;
use PhpAT\Parser\Ast\ReferenceMap;
use PhpAT\Parser\Ast\FullClassName;
use PhpAT\Parser\Relation\Composition;
use PhpAT\Parser\Relation\Dependency;
use PhpAT\Parser\Relation\Inheritance;
use PhpAT\Parser\Relation\Mixin;
use PHPUnit\Framework\TestCase;

abstract class AbstractSelectorTestCase extends TestCase
{
    /**
     * @param ClassLike[] $selected
     * @param string      $classToMatch
     * @return bool
     */
    protected function selectedMatchesClass(array $selected, string $classToMatch): bool
    {
        foreach ($selected as $classLike) {
            if ($classLike->matches($classToMatch)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Fake ReferenceMap for the tests
     */
    protected function getMap(): ReferenceMap
    {
        return new ReferenceMap(
            [
                new SrcNode(
                    new \SplFileInfo('folder/Example/ClassExample.php'),
                    FullClassName::createFromFQCN('Example\\ClassExample'),
                    [
                        new Inheritance(0, FullClassName::createFromFQCN('Example\\ParentClassExample')),
                        new Inheritance(0, FullClassName::createFromFQCN('\\FilterIterator')),
                        new Dependency(0, FullClassName::createFromFQCN('Example\\AnotherClassExample')),
                        new Dependency(0, FullClassName::createFromFQCN('Vendor\\ThirdPartyExample')),
                        new Dependency(0, FullClassName::createFromFQCN('iterable')),
                        new Composition(0, FullClassName::createFromFQCN('Example\\InterfaceExample')),
                        new Composition(0, FullClassName::createFromFQCN('Example\\AnotherInterface')),
                        new Composition(0, FullClassName::createFromFQCN('iterable')),
                        new Mixin(0, FullClassName::createFromFQCN('Example\\TraitExample')),
                        new Mixin(0, FullClassName::createFromFQCN('PHPDocElement'))
                    ]
                )
            ],
            [
                FullClassName::createFromFQCN('iterable'),
                FullClassName::createFromFQCN('\\FilterIterator'),
                FullClassName::createFromFQCN('PHPDocElement'),
            ],
            [
                new ComposerPackage('main', [], [], [], [])
            ]
        );
    }
}
