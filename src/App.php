<?php

declare(strict_types=1);

namespace PhpAT;

use PhpAT\App\Configuration;
use PhpAT\App\Event\SuiteEndEvent;
use PhpAT\App\Event\SuiteStartEvent;
use PhpAT\Parser\Ast\MapBuilder;
use PhpAT\App\RuleValidationStorage;
use PHPAT\EventDispatcher\EventDispatcher;
use PhpAT\Parser\Ast\ReferenceMap;
use PhpAT\Rule\Event\RuleValidationEndEvent;
use PhpAT\Rule\Event\RuleValidationStartEvent;
use PhpAT\Rule\RuleCollection;
use PhpAT\Statement\Statement;
use PhpAT\Statement\StatementBuilder;
use PhpAT\Test\TestExtractor;

class App
{
    /**
     * @var TestExtractor $extractor
     */
    private $extractor;
    /**
     * @var StatementBuilder $statementBuilder
     */
    private $statementBuilder;
    /**
     * @var EventDispatcher
     */
    private $dispatcher;
    /**
     * @var MapBuilder
     */
    private $mapBuilder;
    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * App constructor.
     * @param MapBuilder       $mapBuilder
     * @param TestExtractor    $extractor
     * @param StatementBuilder $statementBuilder
     * @param EventDispatcher  $dispatcher
     * @param Configuration    $configuration
     */
    public function __construct(
        MapBuilder $mapBuilder,
        TestExtractor $extractor,
        StatementBuilder $statementBuilder,
        EventDispatcher $dispatcher,
        Configuration $configuration
    ) {
        $this->extractor        = $extractor;
        $this->statementBuilder = $statementBuilder;
        $this->dispatcher       = $dispatcher;
        $this->mapBuilder       = $mapBuilder;
        $this->configuration    = $configuration;
    }

    /**
     * @throws \Exception
     */
    public function execute(): bool
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

        return !RuleValidationStorage::anyRuleHadErrors() || $this->configuration->getDryRun();
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
