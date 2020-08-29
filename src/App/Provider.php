<?php

declare(strict_types=1);

namespace PhpAT\App;

use PhpAT\App;
use PHPAT\EventDispatcher\EventDispatcher;
use PHPAT\EventDispatcher\ListenerProvider;
use PhpAT\File\FileFinder;
use PhpAT\File\SymfonyFinderAdapter;
use PhpAT\Output\OutputInterface;
use PhpAT\Parser\Ast\Extractor\ExtractorFactory;
use PhpAT\Parser\Ast\MapBuilder;
use PhpAT\Parser\Ast\NodeTraverser;
use PhpAT\Parser\Ast\Type\PhpDocTypeResolver;
use PhpAT\Parser\Ast\Type\PhpStanNodeTypeExtractor;
use PhpAT\Parser\ComposerFileParser;
use PhpAT\Rule\RuleBuilder;
use PhpAT\Rule\Assertion\Composition;
use PhpAT\Rule\Assertion\Dependency;
use PhpAT\Rule\Assertion\Inheritance;
use PhpAT\Rule\Assertion\Mixin;
use PhpAT\Selector\SelectorResolver;
use PhpAT\Statement\StatementBuilder;
use PhpAT\Test\FileTestExtractor;
use PhpAT\Test\Parser\XmlTestParser;
use PhpAT\Test\Parser\YamlTestParser;
use PhpAT\Test\TestExtractor;
use PhpParser\Parser;
use PhpParser\ParserFactory;
use PHPStan\PhpDocParser\Parser\ConstExprParser;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\PhpDocParser\Parser\TypeParser;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Finder\Finder;

/**
 * Class Provider
 *
 * @package PhpAT\App
 */
class Provider
{
    /** @var ContainerBuilder */
    private $builder;
    /** @var OutputInterface */
    private $output;
    /** @var Configuration */
    private $configuration;

    /**
     * Provider constructor.
     * @param ContainerBuilder $builder
     * @param array            $config
     * @param OutputInterface  $output
     */
    public function __construct(ContainerBuilder $builder, array $config, OutputInterface $output)
    {
        $this->configuration = new Configuration($config);
        $this->builder  = $builder;
        $this->output = $output;
    }

    /**
     * @return ContainerBuilder
     */
    public function register(): ContainerBuilder
    {
        $this->builder->set(Configuration::class, $this->configuration);
        $this->builder->set(ComposerFileParser::class, new ComposerFileParser());
        $this->builder->set(Parser::class, (new ParserFactory())->create(ParserFactory::ONLY_PHP7));
        $this->builder->set(PhpDocParser::class, new PhpDocParser(new TypeParser(), new ConstExprParser()));
        $listenerProvider = (new EventListenerMapper())->populateListenerProvider(new ListenerProvider($this->builder));
        $this->builder->set(EventDispatcher::class, (new EventDispatcher($listenerProvider)));
        $this->builder->set(PhpStanNodeTypeExtractor::class, new PhpStanNodeTypeExtractor());

        $this->builder
            ->register(PhpDocTypeResolver::class, PhpDocTypeResolver::class)
            ->addArgument(new Reference(PhpDocParser::class))
            ->addArgument(new Reference(PhpStanNodeTypeExtractor::class));

        $this->builder
            ->register(FileFinder::class, FileFinder::class)
            ->addArgument(new SymfonyFinderAdapter(new Finder()))
            ->addArgument(new Reference(Configuration::class));

        $this->builder
            ->register(NodeTraverser::class, NodeTraverser::class);

        $this->builder
            ->register(ExtractorFactory::class, ExtractorFactory::class)
            ->addArgument(new Reference(PhpDocTypeResolver::class))
            ->addArgument(new Reference(Configuration::class));

        $this->builder
            ->register(MapBuilder::class, MapBuilder::class)
            ->addArgument(new Reference(FileFinder::class))
            ->addArgument(new Reference(ExtractorFactory::class))
            ->addArgument(new Reference(Parser::class))
            ->addArgument(new Reference(NodeTraverser::class))
            ->addArgument(new Reference(PhpDocParser::class))
            ->addArgument(new Reference(EventDispatcher::class))
            ->addArgument(new Reference(ComposerFileParser::class))
            ->addArgument(new Reference(Configuration::class));

        $this->builder
            ->register(RuleBuilder::class, RuleBuilder::class)
            ->addArgument($this->builder);

        $this->builder
            ->register(TestExtractor::class, FileTestExtractor::class)
            ->addArgument(new Reference(RuleBuilder::class))
            ->addArgument(new Reference(EventDispatcher::class))
            ->addArgument(new Reference(YamlTestParser::class))
            ->addArgument(new Reference(XmlTestParser::class))
            ->addArgument(new Reference(Configuration::class));

        $this->builder
            ->register(YamlTestParser::class, YamlTestParser::class)
            ->addArgument(new Reference(RuleBuilder::class))
            ->addArgument(new Reference(EventDispatcher::class));

        $this->builder
            ->register(XmlTestParser::class, XmlTestParser::class)
            ->addArgument(new Reference(RuleBuilder::class))
            ->addArgument(new Reference(EventDispatcher::class));

        $this->builder
            ->register(SelectorResolver::class, SelectorResolver::class)
            ->addArgument($this->builder)
            ->addArgument(new Reference(EventDispatcher::class));

        $this->builder
            ->register(StatementBuilder::class, StatementBuilder::class)
            ->addArgument(new Reference(SelectorResolver::class))
            ->addArgument(new Reference(EventDispatcher::class))
            ->addArgument(new Reference(Configuration::class));

        $this->builder
            ->register(Dependency\MustDepend::class, Dependency\MustDepend::class)
            ->addArgument(new Reference(EventDispatcher::class))
            ->addArgument(new Reference(Configuration::class));

        $this->builder
            ->register(Dependency\MustNotDepend::class, Dependency\MustNotDepend::class)
            ->addArgument(new Reference(EventDispatcher::class))
            ->addArgument(new Reference(Configuration::class));

        $this->builder
            ->register(Dependency\MustOnlyDepend::class, Dependency\MustOnlyDepend::class)
            ->addArgument(new Reference(EventDispatcher::class))
            ->addArgument(new Reference(Configuration::class));

        $this->builder
            ->register(Dependency\CanOnlyDepend::class, Dependency\CanOnlyDepend::class)
            ->addArgument(new Reference(EventDispatcher::class))
            ->addArgument(new Reference(Configuration::class));

        $this->builder
            ->register(Inheritance\MustExtend::class, Inheritance\MustExtend::class)
            ->addArgument(new Reference(EventDispatcher::class))
            ->addArgument(new Reference(Configuration::class));

        $this->builder
            ->register(Inheritance\MustNotExtend::class, Inheritance\MustNotExtend::class)
            ->addArgument(new Reference(EventDispatcher::class))
            ->addArgument(new Reference(Configuration::class));

        $this->builder
            ->register(Inheritance\CanOnlyExtend::class, Inheritance\CanOnlyExtend::class)
            ->addArgument(new Reference(EventDispatcher::class))
            ->addArgument(new Reference(Configuration::class));

        $this->builder
            ->register(Composition\MustImplement::class, Composition\MustImplement::class)
            ->addArgument(new Reference(EventDispatcher::class))
            ->addArgument(new Reference(Configuration::class));

        $this->builder
            ->register(Composition\MustNotImplement::class, Composition\MustNotImplement::class)
            ->addArgument(new Reference(EventDispatcher::class))
            ->addArgument(new Reference(Configuration::class));

        $this->builder
            ->register(Composition\MustOnlyImplement::class, Composition\MustOnlyImplement::class)
            ->addArgument(new Reference(EventDispatcher::class))
            ->addArgument(new Reference(Configuration::class));

        $this->builder
            ->register(Composition\CanOnlyImplement::class, Composition\CanOnlyImplement::class)
            ->addArgument(new Reference(EventDispatcher::class))
            ->addArgument(new Reference(Configuration::class));

        $this->builder
            ->register(Mixin\MustInclude::class, Mixin\MustInclude::class)
            ->addArgument(new Reference(EventDispatcher::class))
            ->addArgument(new Reference(Configuration::class));

        $this->builder
            ->register(Mixin\MustNotInclude::class, Mixin\MustNotInclude::class)
            ->addArgument(new Reference(EventDispatcher::class))
            ->addArgument(new Reference(Configuration::class));

        $this->builder
            ->register(Mixin\MustOnlyInclude::class, Mixin\MustOnlyInclude::class)
            ->addArgument(new Reference(EventDispatcher::class))
            ->addArgument(new Reference(Configuration::class));

        $this->builder
            ->register(Mixin\CanOnlyInclude::class, Mixin\CanOnlyInclude::class)
            ->addArgument(new Reference(EventDispatcher::class))
            ->addArgument(new Reference(Configuration::class));

        $this->builder
            ->register('app', App::class)
            ->addArgument(new Reference(MapBuilder::class))
            ->addArgument(new Reference(TestExtractor::class))
            ->addArgument(new Reference(StatementBuilder::class))
            ->addArgument(new Reference(EventDispatcher::class))
            ->addArgument(new Reference(Configuration::class));

        $listenerProvider = new \PhpAT\App\EventListenerProvider($this->builder, $this->output);
        $this->builder->merge($listenerProvider->register());

        return $this->builder;
    }
}
