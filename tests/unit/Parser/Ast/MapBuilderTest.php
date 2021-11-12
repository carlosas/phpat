<?php declare(strict_types=1);

namespace Tests\PhpAT\unit\Parser\Ast;

use PhpAT\App\Configuration;
use PhpAT\File\FileFinder;
use PhpAT\File\SymfonyFinderAdapter;
use PhpAT\Parser\Ast\Extractor\ExtractorFactory;
use PhpAT\Parser\Ast\MapBuilder;
use PhpAT\Parser\Ast\NodeTraverser;
use PhpAT\Parser\ComposerFileParser;
use PhpParser\Parser;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;
use PhpAT\File\Finder;

class MapBuilderTest extends TestCase
{
    /** @var MockObject<Configuration> */
    protected $configuration;

    /** @var MapBuilder */
    protected $subject;

    public function setUp(): void
    {
        parent::setUp();

        $this->configuration = $this->createMock(Configuration::class);

        $this->subject = new MapBuilder(
            new FileFinder(
                new SymfonyFinderAdapter(new \Symfony\Component\Finder\Finder()),
                $this->configuration
            ),
            $this->createMock(ExtractorFactory::class),
            $this->createMock(Parser::class),
            $this->createMock(NodeTraverser::class),
            $this->createMock(PhpDocParser::class),
            $this->createMock(EventDispatcherInterface::class),
            $this->createMock(ComposerFileParser::class),
            $this->configuration
        );
    }

    public function testDoesNotTreatExcludedFilesAsSource(): void
    {
        $this->configuration
            ->method('getSrcPath')
            ->willReturn(__DIR__ . '/mocks/');
        $this->configuration
            ->method('getSrcExcluded')
            ->willReturn([ 'excluded-directory/' ]);

        $result = $this->subject->build();

        $this->assertArrayHasKey('IncludedClass', $result->getSrcNodes());
        $this->assertArrayNotHasKey('ExcludedClass', $result->getSrcNodes());
    }

}
