<?php

namespace Tests\PhpAT\unit\Selector;

use PhpAT\Parser\Ast\ClassLike;
use PhpAT\Parser\Ast\ComposerPackage;
use PhpAT\Parser\Ast\FullClassName;
use PhpAT\Parser\Ast\ReferenceMap;
use PhpAT\Parser\Ast\SrcNode;
use PhpAT\Parser\Relation\Composition;
use PhpAT\Parser\Relation\Dependency;
use PhpAT\Parser\Relation\Inheritance;
use PhpAT\Parser\Relation\Mixin;
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
                    new FullClassName('Example\\ClassExample'),
                    [
                        new Inheritance(new FullClassName('Example\\ParentClassExample'), 0, 0),
                        new Inheritance(new FullClassName('\\FilterIterator'), 0, 0),
                        new Dependency(new FullClassName('Example\\AnotherClassExample'), 0, 0),
                        new Dependency(new FullClassName('Vendor\\ThirdPartyExample'), 0, 0),
                        new Dependency(new FullClassName('iterable'), 0, 0),
                        new Composition(new FullClassName('Example\\InterfaceExample'), 0, 0),
                        new Composition(new FullClassName('Example\\AnotherInterface'), 0, 0),
                        new Composition(new FullClassName('iterable'), 0, 0),
                        new Mixin(new FullClassName('Example\\TraitExample'), 0, 0),
                        new Mixin(new FullClassName('PHPDocElement'), 0, 0)
                    ]
                )
            ],
            [
                new FullClassName('iterable'),
                new FullClassName('\\FilterIterator'),
                new FullClassName('PHPDocElement'),
            ],
            [
                new ComposerPackage('main', [], [], [], [])
            ]
        );
    }
}
