<?php
declare(strict_types=1);

namespace Tests\PhpAT\unit\Selector;

use PhpAT\Parser\Ast\ClassLike;
use PhpAT\Parser\Ast\FullClassName;
use PhpAT\Parser\Ast\RegexClassName;
use PhpAT\Selector\ClassNameSelector;

class ClassNameSelectorTest extends AbstractSelectorTestCase
{
    public function testSelectsFQCN(): void
    {
        $selected = $this->select('Example\\ClassExample');
        $this->assertTrue($this->selectedMatchesClass($selected, 'Example\\ClassExample'));
    }

    public function testSelectsRegex(): void
    {
        $selected = $this->select('Example\\*');
        $this->assertTrue($this->selectedMatchesClass($selected, 'Example\\ClassExample'));
    }

    public function testSelectsUnknownFQCN(): void
    {
        $selected = $this->select('Fake\\NotAClass');
        $this->assertContainsEquals(FullClassName::createFromFQCN('Fake\\NotAClass'), $selected);
    }

    public function testSelectsUnknownRegex(): void
    {
        $selected = $this->select('Fake\\*');
        $this->assertContainsEquals(new RegexClassName('Fake\\*'), $selected);
    }

    /**
     * @param string $className
     * @return array<ClassLike>
     */
    private function select(string $className): array
    {
        $selector = new ClassNameSelector($className);
        $selector->setReferenceMap($this->getMap());

        return $selector->select();
    }
}
