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
                        new Inheritance(FullClassName::createFromFQCN('Example\\ParentClassExample'), 0, 0),
                        new Inheritance(FullClassName::createFromFQCN('\\FilterIterator'), 0, 0),
                        new Dependency(FullClassName::createFromFQCN('Example\\AnotherClassExample'), 0, 0),
                        new Dependency(FullClassName::createFromFQCN('Vendor\\ThirdPartyExample'), 0, 0),
                        new Dependency(FullClassName::createFromFQCN('iterable'), 0, 0),
                        new Composition(FullClassName::createFromFQCN('Example\\InterfaceExample'), 0, 0),
                        new Composition(FullClassName::createFromFQCN('Example\\AnotherInterface'), 0, 0),
                        new Composition(FullClassName::createFromFQCN('iterable'), 0, 0),
                        new Mixin(FullClassName::createFromFQCN('Example\\TraitExample'), 0, 0),
                        new Mixin(FullClassName::createFromFQCN('PHPDocElement'), 0, 0)
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
