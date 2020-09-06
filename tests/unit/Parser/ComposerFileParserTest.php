<?php
declare(strict_types=1);

namespace Tests\PhpAT\unit\Parser;

use PhpAT\Parser\ComposerFileParser;
use PHPUnit\Framework\TestCase;

class ComposerFileParserTest extends TestCase
{

    /** @var ComposerFileParser */
    private $subject;

    public function setUp(): void
    {
        parent::setUp();

        $this->subject = (new ComposerFileParser())->parse(
            __DIR__ . '/mocks/fake-composer.json',
            __DIR__ . '/mocks/fake-composer.lock'
        );
    }

    public function testExtractsNamespaces(): void
    {
        $this->assertEquals(
            ['Source\\Namespace\\'],
            $this->subject->getNamespaces(false)
        );
        $this->assertEquals(
            ['Test\\Namespace\\'],
            $this->subject->getNamespaces(true)
        );
    }

    public function testShouldExtractDependencies(): void
    {
        $this->assertEquals(
            ['thecodingmachine/safe'],
            $this->subject->getDirectDependencies(false)
        );
        $this->assertEquals(
            ['phpunit/phpunit'],
            $this->subject->getDirectDependencies(true)
        );
    }

    public function testExtractsNamespacesForPackageName()
    {
        $this->assertContains(
            'Safe\\',
            $this->subject->autoloadableNamespacesForRequirements(['thecodingmachine/safe'])
        );
    }

    public function testDeepRequirementNamespacesContainsDepenenciesOfDependencies()
    {
        $namespaces = $this->subject->getDeepRequirementNamespaces(true);

        // phpunit/phpunit depends on doctrine/instantiator
        $this->assertContains('Doctrine\\Instantiator\\', $namespaces);
    }

}
