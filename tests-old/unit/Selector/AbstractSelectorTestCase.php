<?php

namespace Tests\PHPat\unit\Selector;

use PHPat\Parser\Ast\ClassLike;
use PHPat\Parser\Ast\ComposerPackage;
use PHPat\Parser\Ast\FullClassName;
use PHPat\Parser\Ast\ReferenceMap;
use PHPat\Parser\Ast\SrcNode;
use PHPat\Parser\Relation\Composition;
use PHPat\Parser\Relation\Dependency;
use PHPat\Parser\Relation\Inheritance;
use PHPat\Parser\Relation\Mixin;
use PHPUnit\Framework\TestCase;

abstract class AbstractSelectorTestCase extends TestCase
{
    /**
     * @param array<ClassLike> $selected
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
                'Example\\ClassExample' => new SrcNode(
                    'folder/Example/ClassExample.php',
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
