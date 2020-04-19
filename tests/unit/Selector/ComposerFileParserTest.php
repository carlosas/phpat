<?php
declare(strict_types=1);

namespace Tests\PhpAT\unit\Selector;

use PhpAT\Parser\ComposerFileParser;
use PHPUnit\Framework\TestCase;

class ComposerFileParserTest extends TestCase
{

    /** @var ComposerFileParser */
    private $subject;

    public function setUp(): void
    {
        parent::setUp();
        $this->subject = new ComposerFileParser(__DIR__ . '/Mock/composer.json');
    }

    public function testExtractsNamespaces(): void
    {
        $this->assertEquals(
            [ 'Source\\Namespace\\' ],
            $this->subject->getNamespaces(false)
        );
        $this->assertEquals(
            [ 'Source\\Namespace\\', 'Test\\Namespace\\' ],
            $this->subject->getNamespaces(true)
        );
    }

    public function testShouldExtractDependencies(): void
    {
        $this->assertEquals(
            [ 'thecodingmachine/safe' ],
            $this->subject->getDirectDependencies(false)
        );
        $this->assertEquals(
            [ 'thecodingmachine/safe', 'phpunit/phpunit' ],
            $this->subject->getDirectDependencies(true)
        );
    }

    public function testExtractsNamespacesForPackageName()
    {
        $this->assertContains(
            'Safe\\',
            $this->subject->autoloadableNamespacesForRequirements([ 'thecodingmachine/safe' ], false)
        );
    }

    public function testDeepRequirementNamespacesContainsDepenenciesOfDependencies()
    {
        $namespaces = $this->subject->getDeepRequirementNamespaces(true);

        // phpunit/phpunit depends on doctrine/instantiator
        $this->assertContains('Doctrine\\Instantiator\\', $namespaces);
    }

}