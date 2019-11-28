<?php

declare(strict_types=1);

namespace PhpAT\App;

use PhpAT\App;
use PhpAT\File\FileFinder;
use PhpAT\File\SymfonyFinderAdapter;
use PhpAT\Input\InputInterface;
use PhpAT\Output\OutputInterface;
use PhpAT\Output\StdOutput;
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
use PhpParser\ParserFactory;
use PHPStan\PhpDocParser\Parser\ConstExprParser;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\PhpDocParser\Parser\TypeParser;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\EventDispatcher\EventDispatcher as SymfonyEventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
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
        $this->config   = array_merge(
            Yaml::parse(
                file_get_contents(
                    getcwd() . '/' . ($input->getArgument('config-file', 'phpat.yml'))
                )
            ),
            ['options' => $input->getOptions()]
        );
    }

    /**
     * @return ContainerBuilder
     */
    public function register(): ContainerBuilder
    {
        Configuration::init($this->config);
        $phpParser = (new ParserFactory())->create(ParserFactory::ONLY_PHP7);
        $phpDocParser = new PhpDocParser(new TypeParser(), new ConstExprParser());
        $eventDispatcher = new EventDispatcher(new SymfonyEventDispatcher());

        $this->builder
            ->register(EventSubscriberInterface::class, EventSubscriber::class)
            ->addArgument(new Reference(OutputInterface::class));

        $this->builder
            ->register(FileFinder::class, FileFinder::class)
            ->addArgument(new SymfonyFinderAdapter(new Finder()));

        $this->builder
            ->register(NodeTraverserInterface::class, NodeTraverser::class);

        $this->builder
            ->register(OutputInterface::class, StdOutput::class);

        $this->builder
            ->register(RuleBuilder::class, RuleBuilder::class)
            ->addArgument($this->builder);

        $this->builder
            ->register(TestExtractor::class, FileTestExtractor::class)
            ->addArgument(new Reference(RuleBuilder::class));

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
            ->addArgument($phpDocParser)
            ->addArgument($eventDispatcher);

        $this->builder
            ->register(Inheritance::class, Inheritance::class)
            ->addArgument(new Reference(FileFinder::class))
            ->addArgument($phpParser)
            ->addArgument(new Reference(NodeTraverserInterface::class))
            ->addArgument($eventDispatcher);

        $this->builder
            ->register(Composition::class, Composition::class)
            ->addArgument(new Reference(FileFinder::class))
            ->addArgument($phpParser)
            ->addArgument(new Reference(NodeTraverserInterface::class))
            ->addArgument($eventDispatcher);

        $this->builder
            ->register(Mixin::class, Mixin::class)
            ->addArgument(new Reference(FileFinder::class))
            ->addArgument($phpParser)
            ->addArgument(new Reference(NodeTraverserInterface::class))
            ->addArgument($eventDispatcher);

        $this->builder
            ->register('app', App::class)
            ->addArgument(new Reference(TestExtractor::class))
            ->addArgument(new Reference(StatementBuilder::class))
            ->addArgument($eventDispatcher)
            ->addArgument(new Reference(EventSubscriberInterface::class));

        return $this->builder;
    }
}
