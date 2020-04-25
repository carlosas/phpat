<?php
declare(strict_types=1);

namespace Tests\PhpAT\unit\Parser;

use PhpAT\Parser\Ast\ComposerModule;
use PhpAT\Parser\ComposerFileParser;
use PhpAT\Parser\RegexClassName;
use PHPUnit\Framework\TestCase;

class ComposerFileParserTest extends TestCase
{
    /** @var ComposerFileParser */
    private $class;
    /** @var ComposerModule */
    private $result;

    public function setUp(): void
    {
        $this->class = new ComposerFileParser();
        $this->result = $this->class->parse(
            __DIR__ . DIRECTORY_SEPARATOR . 'Mock' . DIRECTORY_SEPARATOR . 'mocked-composer.json',
            __DIR__ . DIRECTORY_SEPARATOR . 'Mock' . DIRECTORY_SEPARATOR . 'mocked-composer.lock'
        );
    }

    public function testParsesFile(): void
    {
        $this->assertInstanceOf(ComposerModule::class, $this->result);
    }


    public function testExtractsNamespaces(): void
    {
        $this->assertEquals(
            [new RegexClassName('Source\Namespace\*')],
            $this->result->getMainAutoloadNamespaces()
        );

        $this->assertEquals(
            [new RegexClassName('Test\Namespace\*')],
            $this->result->getDevAutoloadNamespaces()
        );
    }

    public function testExtractsDependencies(): void
    {
        $this->assertEquals(
            ['php', 'carlosas/simple-event-dispatcher'],
            $this->result->getMainDirectRequirements()
        );

        $this->assertEquals(
            ['phpunit/phpunit'],
            $this->result->getDevDirectRequirements()
        );
    }

    public function testExtractsPackagesNamespaces()
    {
        $this->assertEquals(
            [
                'carlosas/simple-event-dispatcher' => [new RegexClassName('PHPAT\\EventDispatcher\\*')],
                'psr/container' => [new RegexClassName('Psr\\Container\\*')],
                'psr/event-dispatcher' => [new RegexClassName('Psr\\EventDispatcher\\*')],
                'doctrine/instantiator' => [new RegexClassName('Doctrine\\Instantiator\\*')],
                'myclabs/deep-copy' => [new RegexClassName('DeepCopy\\*')],
                'phar-io/manifest' => [],
                'phar-io/version' => [],
                'phpdocumentor/reflection-common' => [new RegexClassName('phpDocumentor\\Reflection\\*')],
                'phpdocumentor/reflection-docblock' => [new RegexClassName('phpDocumentor\\Reflection\\*')],
                'phpdocumentor/type-resolver' => [new RegexClassName('phpDocumentor\\Reflection\\*')],
                'phpspec/prophecy' => [new RegexClassName('Prophecy\\*')],
                'phpunit/php-code-coverage' => [],
                'phpunit/php-file-iterator' => [],
                'phpunit/php-text-template' => [],
                'phpunit/php-timer' => [],
                'phpunit/php-token-stream' => [],
                'phpunit/phpunit' => [],
                'sebastian/code-unit-reverse-lookup' => [],
                'sebastian/comparator' => [],
                'sebastian/diff' => [],
                'sebastian/environment' => [],
                'sebastian/exporter' => [],
                'sebastian/global-state' => [],
                'sebastian/object-enumerator' => [],
                'sebastian/object-reflector' => [],
                'sebastian/recursion-context' => [],
                'sebastian/resource-operations' => [],
                'sebastian/type' => [],
                'sebastian/version' => [],
                'symfony/polyfill-ctype' => [new RegexClassName('Symfony\\Polyfill\\Ctype\\*')],
                'theseer/tokenizer' => [],
                'webmozart/assert' => [new RegexClassName('Webmozart\\Assert\\*')]
            ],
            $this->result->getAllPackagesNamespaces()
        );
    }
    /*
     * TODO
     */
    /*public function testDeepRequirementNamespacesContainsDepenenciesOfDependencies()
    {
        $namespaces = $this->result->getDeepDependencies();


        // phpunit/phpunit depends on doctrine/instantiator
        $this->assertContains('Doctrine\\Instantiator\\', $namespaces);
    }*/
}