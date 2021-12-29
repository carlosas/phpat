<?php

declare(strict_types=1);

namespace PhpAT\App;

use PhpAT\Rule\Baseline;
use PHPAT\EventDispatcher\EventDispatcher;
use PHPAT\EventDispatcher\ListenerProvider;
use PhpAT\File\FileFinder;
use PhpAT\File\SymfonyFinderAdapter;
use PhpAT\Parser\Ast\MapBuilder;
use PhpAT\Parser\Ast\Traverser\TraverserFactory;
use PhpAT\Parser\Ast\Type\PhpStanDocTypeNodeResolver;
use PhpAT\Parser\Ast\Type\PhpStanDocNodeTypeExtractor;
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
use PhpParser\Lexer\Emulative;
use PhpParser\Parser;
use PhpParser\ParserFactory;
use PHPStan\PhpDocParser\Parser\ConstExprParser;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\PhpDocParser\Parser\TypeParser;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Finder\Finder;

class Provider
{
    private ContainerBuilder $builder;
    private OutputInterface $output;
    private Configuration $configuration;

    /**
     * Provider constructor.
     */
    public function __construct(ContainerBuilder $builder, Configuration $configuration, OutputInterface $output)
    {
        $this->configuration = $configuration;
        $this->builder       = $builder;
        $this->output        = $output;
    }

    public function register(): ContainerBuilder
    {
        $this->builder->set(Configuration::class, $this->configuration);
        $this->builder->set(ComposerFileParser::class, new ComposerFileParser());
        $phpVersion   = $this->configuration->getPhpVersion();
        $lexerOptions = $phpVersion ? ['phpVersion' => $phpVersion] : [];
        $this->builder->set(
            Parser::class,
            (new ParserFactory())->create(ParserFactory::ONLY_PHP7, new Emulative($lexerOptions))
        );
        $this->builder->set(PhpDocParser::class, new PhpDocParser(new TypeParser(), new ConstExprParser()));
        $listenerProvider = (new EventListenerMapper())->populateListenerProvider(new ListenerProvider($this->builder));
        $this->builder->set(EventDispatcherInterface::class, (new EventDispatcher($listenerProvider)));
        $this->builder->set(PhpStanDocNodeTypeExtractor::class, new PhpStanDocNodeTypeExtractor());

        $this->builder->register(Baseline::class, Baseline::class)
            ->addArgument($this->configuration)
            ->addArgument(new Reference(EventDispatcherInterface::class));

        $this->builder
            ->register(PhpStanDocTypeNodeResolver::class, PhpStanDocTypeNodeResolver::class)
            ->addArgument(new Reference(PhpDocParser::class))
            ->addArgument(new Reference(PhpStanDocNodeTypeExtractor::class));

        $this->builder->register(TraverserFactory::class, TraverserFactory::class)
            ->addArgument(new Reference(Configuration::class))
            ->addArgument(new Reference(PhpStanDocTypeNodeResolver::class));

        $this->builder
            ->register(FileFinder::class, FileFinder::class)
            ->addArgument(new SymfonyFinderAdapter(new Finder()))
            ->addArgument(new Reference(Configuration::class));

        $this->builder
            ->register(MapBuilder::class, MapBuilder::class)
            ->addArgument(new Reference(FileFinder::class))
            ->addArgument(new Reference(Parser::class))
            ->addArgument(new Reference(TraverserFactory::class))
            ->addArgument(new Reference(EventDispatcherInterface::class))
            ->addArgument(new Reference(ComposerFileParser::class))
            ->addArgument(new Reference(Configuration::class));

        $this->builder
            ->register(RuleBuilder::class, RuleBuilder::class)
            ->addArgument($this->builder);

        $this->builder
            ->register(TestExtractor::class, FileTestExtractor::class)
            ->addArgument(new Reference(RuleBuilder::class))
            ->addArgument(new Reference(EventDispatcherInterface::class))
            ->addArgument(new Reference(YamlTestParser::class))
            ->addArgument(new Reference(XmlTestParser::class))
            ->addArgument(new Reference(Configuration::class));

        $this->builder
            ->register(YamlTestParser::class, YamlTestParser::class)
            ->addArgument(new Reference(RuleBuilder::class))
            ->addArgument(new Reference(EventDispatcherInterface::class));

        $this->builder
            ->register(XmlTestParser::class, XmlTestParser::class)
            ->addArgument(new Reference(RuleBuilder::class))
            ->addArgument(new Reference(EventDispatcherInterface::class));

        $this->builder
            ->register(SelectorResolver::class, SelectorResolver::class)
            ->addArgument($this->builder)
            ->addArgument(new Reference(EventDispatcherInterface::class));

        $this->builder
            ->register(StatementBuilder::class, StatementBuilder::class)
            ->addArgument(new Reference(SelectorResolver::class))
            ->addArgument(new Reference(EventDispatcherInterface::class))
            ->addArgument(new Reference(Configuration::class));

        $this->builder
            ->register(Dependency\MustDepend::class, Dependency\MustDepend::class)
            ->addArgument(new Reference(EventDispatcherInterface::class))
            ->addArgument(new Reference(Configuration::class));

        $this->builder
            ->register(Dependency\MustNotDepend::class, Dependency\MustNotDepend::class)
            ->addArgument(new Reference(EventDispatcherInterface::class))
            ->addArgument(new Reference(Configuration::class));

        $this->builder
            ->register(Dependency\MustOnlyDepend::class, Dependency\MustOnlyDepend::class)
            ->addArgument(new Reference(EventDispatcherInterface::class))
            ->addArgument(new Reference(Configuration::class));

        $this->builder
            ->register(Dependency\CanOnlyDepend::class, Dependency\CanOnlyDepend::class)
            ->addArgument(new Reference(EventDispatcherInterface::class))
            ->addArgument(new Reference(Configuration::class));

        $this->builder
            ->register(Inheritance\MustExtend::class, Inheritance\MustExtend::class)
            ->addArgument(new Reference(EventDispatcherInterface::class))
            ->addArgument(new Reference(Configuration::class));

        $this->builder
            ->register(Inheritance\MustNotExtend::class, Inheritance\MustNotExtend::class)
            ->addArgument(new Reference(EventDispatcherInterface::class))
            ->addArgument(new Reference(Configuration::class));

        $this->builder
            ->register(Inheritance\CanOnlyExtend::class, Inheritance\CanOnlyExtend::class)
            ->addArgument(new Reference(EventDispatcherInterface::class))
            ->addArgument(new Reference(Configuration::class));

        $this->builder
            ->register(Composition\MustImplement::class, Composition\MustImplement::class)
            ->addArgument(new Reference(EventDispatcherInterface::class))
            ->addArgument(new Reference(Configuration::class));

        $this->builder
            ->register(Composition\MustNotImplement::class, Composition\MustNotImplement::class)
            ->addArgument(new Reference(EventDispatcherInterface::class))
            ->addArgument(new Reference(Configuration::class));

        $this->builder
            ->register(Composition\MustOnlyImplement::class, Composition\MustOnlyImplement::class)
            ->addArgument(new Reference(EventDispatcherInterface::class))
            ->addArgument(new Reference(Configuration::class));

        $this->builder
            ->register(Composition\CanOnlyImplement::class, Composition\CanOnlyImplement::class)
            ->addArgument(new Reference(EventDispatcherInterface::class))
            ->addArgument(new Reference(Configuration::class));

        $this->builder
            ->register(Mixin\MustInclude::class, Mixin\MustInclude::class)
            ->addArgument(new Reference(EventDispatcherInterface::class))
            ->addArgument(new Reference(Configuration::class));

        $this->builder
            ->register(Mixin\MustNotInclude::class, Mixin\MustNotInclude::class)
            ->addArgument(new Reference(EventDispatcherInterface::class))
            ->addArgument(new Reference(Configuration::class));

        $this->builder
            ->register(Mixin\MustOnlyInclude::class, Mixin\MustOnlyInclude::class)
            ->addArgument(new Reference(EventDispatcherInterface::class))
            ->addArgument(new Reference(Configuration::class));

        $this->builder
            ->register(Mixin\CanOnlyInclude::class, Mixin\CanOnlyInclude::class)
            ->addArgument(new Reference(EventDispatcherInterface::class))
            ->addArgument(new Reference(Configuration::class));

        $listenerProvider = new EventListenerProvider($this->builder, $this->output);
        $this->builder->merge($listenerProvider->register());

        return $this->builder;
    }
}
