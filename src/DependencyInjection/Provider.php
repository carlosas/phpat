<?php declare(strict_types=1);

namespace PhpAT\DependencyInjection;

use PhpAT\File\FileFinder;
use PhpAT\File\SymfonyFinderAdapter;
use PhpAT\Rule\Type\Composition;
use PhpAT\Rule\Type\Dependency;
use PhpAT\Rule\Type\Inheritance;
use PhpAT\Rule\RuleBuilder;
use PhpAT\Statement\StatementBuilder;
use PhpAT\Subscriber\EventSubscriber;
use PhpAT\Test\FileTestExtractor;
use PhpAT\Test\TestExtractor;
use PhpAT\App;
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

class Provider
{
    private $builder;
    private $autoload;
    private $configuration;

    public function __construct(ContainerBuilder $builder, string $autoload, array $argv)
    {
        $this->builder = $builder;
        $this->autoload = $autoload;
        $this->configuration = new Configuration(Yaml::parseFile(getcwd() . '/' . ($argv[1] ?? 'phpat.yml')));
    }

    public function register(): ContainerBuilder
    {
        $phpParser = (new ParserFactory())->create(ParserFactory::ONLY_PHP7);

        $this->builder
            ->register(EventDispatcherInterface::class, EventDispatcher::class);

        $this->builder
            ->register(EventSubscriberInterface::class, EventSubscriber::class);

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
            ->register(RuleBuilder::class, RuleBuilder::class)
            ->addArgument($this->builder);

        $this->builder
            ->register(TestExtractor::class, FileTestExtractor::class)
            ->addArgument(new Reference(RuleBuilder::class))
            ->addArgument(getcwd() . '/' . $this->configuration->getTestsPath());

        $this->builder
            ->register(StatementBuilder::class, StatementBuilder::class)
            ->addArgument(new Reference(FileFinder::class))
            ->addArgument($phpParser);

        $this->builder
            ->register(Dependency::class, Dependency::class)
            ->addArgument(new Reference(FileFinder::class))
            ->addArgument($phpParser)
            ->addArgument(new Reference(NodeTraverserInterface::class))
            ->addArgument(new Reference(EventDispatcherInterface::class));

        $this->builder
            ->register(Inheritance::class, Inheritance::class)
            ->addArgument(new Reference(FileFinder::class))
            ->addArgument($phpParser)
            ->addArgument(new Reference(NodeTraverserInterface::class))
            ->addArgument(new Reference(EventDispatcherInterface::class));

        $this->builder
            ->register(Composition::class, Composition::class)
            ->addArgument(new Reference(FileFinder::class))
            ->addArgument($phpParser)
            ->addArgument(new Reference(NodeTraverserInterface::class))
            ->addArgument(new Reference(EventDispatcherInterface::class));

        $this->builder
            ->register('app', App::class)
            ->addArgument(new Reference(TestExtractor::class))
            ->addArgument(new Reference(StatementBuilder::class))
            ->addArgument(new Reference(EventDispatcherInterface::class))
            ->addArgument(new Reference(EventSubscriberInterface::class));

        return $this->builder;
    }
}
