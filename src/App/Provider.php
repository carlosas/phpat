<?php

declare(strict_types=1);

namespace PhpAT\App;

use PhpAT\App;
use PHPAT\EventDispatcher\EventDispatcher;
use PHPAT\EventDispatcher\ListenerProvider;
use PhpAT\File\FileFinder;
use PhpAT\File\SymfonyFinderAdapter;
use PhpAT\Input\InputInterface;
use PhpAT\Output\OutputInterface;
use PhpAT\Output\StdOutput;
use PhpAT\Parser\MapBuilder;
use PhpAT\Rule\RuleBuilder;
use PhpAT\Rule\Type\Composition;
use PhpAT\Rule\Type\Dependency;
use PhpAT\Rule\Type\Inheritance;
use PhpAT\Rule\Type\Mixin;
use PhpAT\Selector\SelectorResolver;
use PhpAT\Statement\StatementBuilder;
use PhpAT\Test\FileTestExtractor;
use PhpAT\Test\TestExtractor;
use PhpParser\NodeTraverser;
use PhpParser\NodeTraverserInterface;
use PhpParser\Parser;
use PhpParser\ParserFactory;
use PHPStan\PhpDocParser\Parser\ConstExprParser;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\PhpDocParser\Parser\TypeParser;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

/**
 * Class Provider
 *
 * @package PhpAT\App
 */
class Provider
{
    /**
     * @var ContainerBuilder
     */
    private $builder;
    /**
     * @var string
     */
    private $autoload;
    /**
     * @var array
     */
    private $config;

    /**
     * Provider constructor.
     *
     * @param ContainerBuilder $builder
     * @param string           $autoload
     * @param InputInterface   $input
     */
    public function __construct(ContainerBuilder $builder, string $autoload, InputInterface $input)
    {
        $this->builder  = $builder;
        $this->autoload = $autoload;
        $this->config = Yaml::parse(
            file_get_contents(
                getcwd() . '/' . ($input->getArgument('config-file', 'phpat.yml'))
            )
        );
        $this->config['options'] = array_merge($this->config['options'] ?? [], $input->getOptions());
    }

    /**
     * @return ContainerBuilder
     */
    public function register(): ContainerBuilder
    {
        Configuration::init($this->config);
        $this->builder->set(Parser::class, (new ParserFactory())->create(ParserFactory::ONLY_PHP7));
        $phpDocParser = new PhpDocParser(new TypeParser(), new ConstExprParser());
        $this->builder->set(Parser::class, (new ParserFactory())->create(ParserFactory::ONLY_PHP7));

        $this->builder->set(PhpDocParser::class, new PhpDocParser(new TypeParser(), new ConstExprParser()));

        $listenerProvider = (new EventListenerMapper())->populateListenerProvider(new ListenerProvider($this->builder));
        $this->builder->set(EventDispatcher::class, (new EventDispatcher($listenerProvider)));

        $this->builder
            ->register(FileFinder::class, FileFinder::class)
            ->addArgument(new SymfonyFinderAdapter(new Finder()));

        $this->builder
            ->register(NodeTraverserInterface::class, NodeTraverser::class);

        $this->builder
            ->register(MapBuilder::class, MapBuilder::class)
            ->addArgument(new Reference(FileFinder::class))
            ->addArgument(new Reference(Parser::class))
            ->addArgument(new Reference(NodeTraverserInterface::class))
            ->addArgument($phpDocParser);

        $this->builder
            ->register(OutputInterface::class, StdOutput::class);

        $this->builder
            ->register(RuleBuilder::class, RuleBuilder::class)
            ->addArgument($this->builder);

        $this->builder
            ->register(TestExtractor::class, FileTestExtractor::class)
            ->addArgument(new Reference(RuleBuilder::class))
            ->addArgument(new Reference(EventDispatcher::class));

        $this->builder
            ->register(SelectorResolver::class, SelectorResolver::class)
            ->addArgument($this->builder);

        $this->builder
            ->register(StatementBuilder::class, StatementBuilder::class)
            ->addArgument(new Reference(SelectorResolver::class))
            ->addArgument(new Reference(Parser::class));

        $this->builder
            ->register(Dependency\MustDepend::class, Dependency\MustDepend::class)
            ->addArgument(new Reference(EventDispatcher::class));

        $this->builder
            ->register(Dependency\CanOnlyDepend::class, Dependency\CanOnlyDepend::class)
            ->addArgument(new Reference(EventDispatcher::class));

        $this->builder
            ->register(Inheritance\MustExtend::class, Inheritance\MustExtend::class)
            ->addArgument(new Reference(EventDispatcher::class));

        $this->builder
            ->register(Inheritance\CanOnlyExtend::class, Inheritance\CanOnlyExtend::class)
            ->addArgument(new Reference(EventDispatcher::class));

        $this->builder
            ->register(Composition\MustImplement::class, Composition\MustImplement::class)
            ->addArgument(new Reference(EventDispatcher::class));

        $this->builder
            ->register(Composition\MustOnlyImplement::class, Composition\MustOnlyImplement::class)
            ->addArgument(new Reference(EventDispatcher::class));

        $this->builder
            ->register(Composition\CanOnlyImplement::class, Composition\CanOnlyImplement::class)
            ->addArgument(new Reference(EventDispatcher::class));

        $this->builder
            ->register(Mixin\MustInclude::class, Mixin\MustInclude::class)
            ->addArgument(new Reference(EventDispatcher::class));

        $this->builder
            ->register(Mixin\CanOnlyInclude::class, Mixin\CanOnlyInclude::class)
            ->addArgument(new Reference(EventDispatcher::class));

        $this->builder
            ->register('app', App::class)
            ->addArgument(new Reference(MapBuilder::class))
            ->addArgument(new Reference(TestExtractor::class))
            ->addArgument(new Reference(StatementBuilder::class))
            ->addArgument(new Reference(EventDispatcher::class));

        $listenerProvider = new \PhpAT\App\ListenerProvider($this->builder);
        $this->builder->merge($listenerProvider->register());

        return $this->builder;
    }
}
