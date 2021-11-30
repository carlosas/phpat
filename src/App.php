<?php

namespace PhpAT;

use PhpAT\App\Cli\SingleCommandApplication;
use PhpAT\App\Event\SuiteEndEvent;
use PhpAT\App\Event\SuiteStartEvent;
use PhpAT\App\Provider;
use PhpAT\App\ErrorStorage;
use PhpAT\Rule\Baseline;
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
    private ?TestExtractor $extractor = null;
    private ?StatementBuilder $statementBuilder = null;
    private ?EventDispatcher $dispatcher = null;
    private ?MapBuilder $mapBuilder = null;
    private ?Baseline $baseline = null;

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
                'baseline',
                null,
                InputOption::VALUE_REQUIRED,
                'Path to baseline file to use'
            )
            ->addOption(
                'generate-baseline',
                null,
                InputOption::VALUE_REQUIRED,
                'Generate a baseline file and exit without error code'
            )
            ->addOption(
                'php-version',
                null,
                InputOption::VALUE_REQUIRED,
                'PHP version of the src code'
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

        $configuration = (new ConfigurationFactory())->create(
            $input->getArgument('config'),
            $input->getOptions()
        );

        if (!$this->hasCommandVerbosity($output)) {
            $output->setVerbosity($this->getConsoleVerbosity($configuration->getVerbosity()));
        }

        $provider = new Provider(
            new ContainerBuilder(),
            $configuration,
            $output
        );
        $container = $provider->register();

        $this->extractor = $container->get(TestExtractor::class);
        $this->statementBuilder = $container->get(StatementBuilder::class);
        $this->dispatcher = $container->get(EventDispatcher::class);
        $this->mapBuilder = $container->get(MapBuilder::class);
        $this->baseline = $container->get(Baseline::class);
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

        $this->baseline->checkNonCompensatedErrors();
        $baselineGenerated = $this->baseline->generateBaselineFileIfNeeded();

        $this->dispatcher->dispatch(new SuiteEndEvent());

        return ($baselineGenerated || (ErrorStorage::getTotalErrors() === 0)) ? 0 : 1;
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

    private function hasCommandVerbosity(OutputInterface $output): bool
    {
        return $output->getVerbosity() !== OutputInterface::VERBOSITY_NORMAL;
    }

    private function getConsoleVerbosity(int $verbosity): int
    {
        switch ($verbosity) {
            case 2:
                return OutputInterface::VERBOSITY_VERY_VERBOSE;
            case 1:
                return OutputInterface::VERBOSITY_VERBOSE;
            case -1:
                return OutputInterface::VERBOSITY_QUIET;
            case 0:
            default:
                return OutputInterface::VERBOSITY_NORMAL;
        }
    }
}
