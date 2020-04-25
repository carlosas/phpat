<?php
declare(strict_types=1);

namespace Tests\PhpAT\unit\Selector;

use PhpAT\Parser\Ast\ComposerModule;
use PhpAT\Parser\Ast\ReferenceMap;
use PhpAT\Parser\ClassLike;
use PhpAT\Parser\RegexClassName;
use PhpAT\Selector\ComposerDependencySelector;
use PhpAT\Selector\ComposerSourceSelector;
use PHPUnit\Framework\TestCase;

class ComposerDependencySelectorTest extends TestCase
{
    /** @var ComposerDependencySelector */
    private $class;

    protected function setUp(): void
    {
        $map = new ReferenceMap(
            [],
            [
                'somealias' => new ComposerModule(
                    [new RegexClassName('Source\Namespace\*')],
                    [new RegexClassName('Test\Namespace\*')],
                    [],
                    [],
                    [],
                    []
                )
            ]
        );
        $this->class = new ComposerDependencySelector('somealias');
        $this->class->setReferenceMap($map);
    }
/*
    public function testSelectsSourceDirectories(): void
    {
        $selected = $this->class->select();
        $this->assertTrue($this->oneSelectedMatches($selected, 'Source\\Namespace\\Foo'));
    }

    public function testDoesNotSelectDevDirectories(): void
    {
        $selected = $this->class->select();
        $this->assertFalse($this->oneSelectedMatches($selected, 'Test\\Namespace\\Foo'));
    }
*/
    /**
     * @param ClassLike[] $selected
     * @param string $classToMatch
     * @return bool
     */
    private function oneSelectedMatches(array $selected, string $classToMatch): bool
    {
        foreach ($selected as $classLike) {
            if ($classLike->matches($classToMatch)) {
                return true;
            }
        }

        return false;
    }
}
