<?php

namespace PhpAT;

use PhpAT\App\Cli\SingleCommandApplication;
use PhpAT\App\Configuration;
use PhpAT\App\Event\SuiteEndEvent;
use PhpAT\App\Event\SuiteStartEvent;
use PhpAT\App\Provider;
use PhpAT\App\RuleValidationStorage;
use PhpAT\Config\ConfigurationFactory;
use PHPAT\EventDispatcher\EventDispatcher;
use PhpAT\Parser\Ast\MapBuilder;
use PhpAT\Parser\Ast\ReferenceMap;
use PhpAT\Rule\Event\RuleValidationEndEvent;
use PhpAT\Rule\Event\RuleValidationStartEvent;
use PhpAT\Rule\RuleCollection;
use PhpAT\Statement\Statement;
use PhpAT\Statement\StatementBuilder;
use PhpAT\Test\TestExtractor;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class App extends SingleCommandApplication
{
    /** @var TestExtractor */
    private $extractor;
    /** * @var StatementBuilder */
    private $statementBuilder;
    /** * @var EventDispatcher */
    private $dispatcher;
    /** * @var MapBuilder */
    private $mapBuilder;
    /** * @var Configuration */
    private $configuration;

    protected function configure()
    {
        $this
            ->addArgument(
                'config',
                InputArgument::OPTIONAL,
                'Configuration file',
                file_exists('phpat.yaml') ? 'phpat.yaml' : 'phpat.yml'
            )
            ->addOption(
                'ignore-docblocks',
                null,
                InputOption::VALUE_REQUIRED,
                'Ignore relations in docblocks'
            )
            ->addOption(
                'ignore-php-extensions',
                null,
                InputOption::VALUE_REQUIRED,
                'Ignore relations to core and extensions classes'
            );
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        if (!(file_exists($input->getArgument('config')))) {
            throw new \RuntimeException('Configuration file not found.');
        }

        $provider = new Provider(
            new ContainerBuilder(),
            (new ConfigurationFactory())->create($input->getArgument('config'), $input->getOptions()),
            $output
        );
        $container = $provider->register();

        $this->extractor = $container->get(TestExtractor::class);
        $this->statementBuilder = $container->get(StatementBuilder::class);
        $this->dispatcher = $container->get(EventDispatcher::class);
        $this->configuration = $container->get(Configuration::class);
        $this->mapBuilder = $container->get(MapBuilder::class);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->dispatcher->dispatch(new SuiteStartEvent());

        $map = $this->mapBuilder->build();

        $testSuite = $this->extractor->execute();

        $rules = new RuleCollection();
        foreach ($testSuite->getValues() as $test) {
            $rules = $rules->merge($test());
        }

        foreach ($rules->getValues() as $rule) {
            $statements = $this->statementBuilder->build($rule, $map);

            $this->dispatcher->dispatch(new RuleValidationStartEvent($rule->getName()));
            /** @var Statement $statement */
            foreach ($statements as $statement) {
                $this->validateStatement($statement, $map);
            }

            $this->dispatcher->dispatch(new RuleValidationEndEvent());
        }

        $this->dispatcher->dispatch(new SuiteEndEvent());

        return (int) !(RuleValidationStorage::getTotalErrors() === 0);
    }

    private function validateStatement(Statement $statement, ReferenceMap $map): void
    {
        $statement->getAssertion()->validate(
            $statement->getOrigin(),
            $statement->getDestinations(),
            $statement->getExcludedDestinations(),
            $map
        );
    }
}
