<?php declare(strict_types=1);

namespace PHPArchiTest\DependencyInjection;

use PHPArchiTest\File\FileFinder;
use PHPArchiTest\File\SymfonyFinderAdapter;
use PHPArchiTest\Parser\Parser;
use PHPArchiTest\Statement\StatementBuilder;
use PHPArchiTest\Test\FileTestExtractor;
use PHPArchiTest\Test\TestExtractor;
use PHPArchiTest\Validation\Validator;
use PHPArchiTest\App;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

class ServiceProvider
{
    private $builder;
    private $autoload;
    private $configuration;

    public function __construct(ContainerBuilder $builder, string $autoload, array $argv)
    {
        $this->builder = $builder;
        $this->autoload = $autoload;
        $this->configuration = new Configuration(Yaml::parseFile(getcwd().'/'.($argv[1] ?? 'phpat.yml')));
    }

    public function register(): ContainerBuilder
    {
        $this->builder
            ->register(FileFinder::class, FileFinder::class)
            ->addArgument(new SymfonyFinderAdapter(new Finder()))
            ->addArgument($this->configuration);

        $this->builder
            ->register(Parser::class, Parser::class)
            ->addArgument($this->configuration)
            ->addArgument($this->autoload);

        $this->builder
            ->register(StatementBuilder::class, StatementBuilder::class)
            ->addArgument(new Reference(FileFinder::class))
            ->addArgument(new Reference(Parser::class));

        $this->builder
            ->register(Validator::class, Validator::class);

        $this->builder
            ->register(TestExtractor::class, FileTestExtractor::class)
            ->addArgument(getcwd().'/'.$this->configuration->getTestsPath());

        $this->builder
            ->register('app', App::class)
            ->addArgument(new Reference(TestExtractor::class))
            ->addArgument(new Reference(StatementBuilder::class))
            ->addArgument(new Reference(Validator::class));

        return $this->builder;
    }
}
