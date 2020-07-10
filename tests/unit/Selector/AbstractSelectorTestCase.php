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
                    new FullClassName('Example', 'ClassExample'),
                    [
                        new Inheritance(0, new FullClassName('Example', 'ParentClassExample')),
                        new Inheritance(0, new FullClassName('', 'FilterIterator')),
                        new Dependency(0, new FullClassName('Example', 'AnotherClassExample')),
                        new Dependency(0, new FullClassName('Vendor', 'ThirdPartyExample')),
                        new Dependency(0, new FullClassName('', 'iterable')),
                        new Composition(0, new FullClassName('Example', 'InterfaceExample')),
                        new Composition(0, new FullClassName('Example', 'AnotherInterface')),
                        new Composition(0, new FullClassName('', 'iterable')),
                        new Mixin(0, new FullClassName('Example', 'TraitExample')),
                        new Mixin(0, new FullClassName('', 'PHPDocElement'))
                    ]
                )
            ],
            [
                new FullClassName('', 'iterable'),
                new FullClassName('', 'FilterIterator'),
                new FullClassName('', 'PHPDocElement'),
            ],
            [
                new ComposerPackage('main', [], [], [], [])
            ]
        );
    }
}
