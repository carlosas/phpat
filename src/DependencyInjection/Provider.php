<?php

declare(strict_types=1);

namespace PhpAT\DependencyInjection;

use PhpAT\App;
use PhpAT\File\FileFinder;
use PhpAT\File\SymfonyFinderAdapter;
use PhpAT\Output\OutputInterface;
use PhpAT\Output\StdOutput;
use PhpAT\Rule\RuleBuilder;
use PhpAT\Rule\Type\Composition;
use PhpAT\Rule\Type\Dependency;
use PhpAT\Rule\Type\Inheritance;
use PhpAT\Selector\SelectorResolver;
use PhpAT\Shared\EventSubscriber;
use PhpAT\Statement\StatementBuilder;
use PhpAT\Test\FileTestExtractor;
use PhpAT\Test\TestExtractor;
use PhpParser\NodeTraverser;
use PhpParser\NodeTraverserInterface;
use PhpParser\ParserFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

/**
 * Class Provider
 * @package PhpAT\DependencyInjection
 */
class Provider
{
    /** @var ContainerBuilder */
    private $builder;
    /** @var string */
    private $autoload;
    /** @var Configuration */
    private $configuration;

    /**
     * Provider constructor.
     * @param ContainerBuilder $builder
     * @param string $autoload
     * @param array $argv
     */
    public function __construct(ContainerBuilder $builder, string $autoload, array $argv)
    {
        $this->builder       = $builder;
        $this->autoload      = $autoload;
        $this->configuration = new Configuration(Yaml::parseFile(getcwd() . '/' . ($argv[1] ?? 'phpat.yml')));
    }

    /**
     * @return ContainerBuilder
     */
    public function register(): ContainerBuilder
    {
        $phpParser = (new ParserFactory())->create(ParserFactory::ONLY_PHP7);

        $this->builder
            ->register(EventDispatcherInterface::class, EventDispatcher::class);

        $this->builder
            ->register(EventSubscriberInterface::class, EventSubscriber::class)
            ->addArgument(new Reference(OutputInterface::class));

        $this->builder
            ->register(FileFinder::class, FileFinder::class)
            ->addArgument(new SymfonyFinderAdapter(new Finder()))
            ->addArgument($this->configuration);

        $this->builder
            ->register(FileFinder::class, FileFinder::class)
            ->addArgument(new SymfonyFinderAdapter(new Finder()))
            ->addArgument($this->configuration);

        $this->builder
            ->register(NodeTraverserInterface::class, NodeTraverser::class);

        $this->builder
            ->register(OutputInterface::class, StdOutput::class);

        $this->builder
            ->register(RuleBuilder::class, RuleBuilder::class)
            ->addArgument($this->builder);

        $this->builder
            ->register(TestExtractor::class, FileTestExtractor::class)
            ->addArgument(new Reference(RuleBuilder::class))
            ->addArgument(getcwd() . '/' . $this->configuration->getTestsPath());

        $this->builder
            ->register(SelectorResolver::class, SelectorResolver::class)
            ->addArgument($this->builder);

        $this->builder
            ->register(StatementBuilder::class, StatementBuilder::class)
            ->addArgument(new Reference(SelectorResolver::class))
            ->addArgument($phpParser);

        $this->builder
            ->register(Dependency::class, Dependency::class)
            ->addArgument(new Reference(FileFinder::class))
            ->addArgument($phpParser)
            ->addArgument(new Reference(NodeTraverserInterface::class))
            ->addArgument(new Reference(EventDispatcherInterface::class))
            ->addArgument(new Reference(OutputInterface::class));

        $this->builder
            ->register(Inheritance::class, Inheritance::class)
            ->addArgument(new Reference(FileFinder::class))
            ->addArgument($phpParser)
            ->addArgument(new Reference(NodeTraverserInterface::class))
            ->addArgument(new Reference(EventDispatcherInterface::class))
            ->addArgument(new Reference(OutputInterface::class));

        $this->builder
            ->register(Composition::class, Composition::class)
            ->addArgument(new Reference(FileFinder::class))
            ->addArgument($phpParser)
            ->addArgument(new Reference(NodeTraverserInterface::class))
            ->addArgument(new Reference(EventDispatcherInterface::class))
            ->addArgument(new Reference(OutputInterface::class));

        $this->builder
            ->register('app', App::class)
            ->addArgument(new Reference(TestExtractor::class))
            ->addArgument(new Reference(StatementBuilder::class))
            ->addArgument(new Reference(EventDispatcherInterface::class))
            ->addArgument(new Reference(EventSubscriberInterface::class))
            ->addArgument(new Reference(OutputInterface::class));

        return $this->builder;
    }
}
