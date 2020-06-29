<?php
declare(strict_types=1);

namespace Tests\PhpAT\unit\Parser;

use PhpAT\App\Configuration;
use PhpAT\Parser\ComposerFileParser;
use PHPUnit\Framework\TestCase;

class ComposerFileParserTest extends TestCase
{

    /** @var ComposerFileParser */
    private $subject;

    public function setUp(): void
    {
        parent::setUp();

        $configurationMock = $this->createMock(Configuration::class);
        $configurationMock->method('getComposerConfiguration')->willReturn([
            'main' => [
               'json' => __DIR__ . '/Mock/fake-composer.json',
               'lock' => __DIR__ . '/Mock/fake-composer.lock'
            ]
        ]);
        $this->subject = (new ComposerFileParser())->parse($configurationMock, 'main');
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
