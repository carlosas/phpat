<?php

declare(strict_types=1);

namespace PhpAT;

use PhpAT\App\Configuration;
use PhpAT\App\Event\SuiteEndEvent;
use PhpAT\App\Event\SuiteStartEvent;
use PhpAT\App\EventDispatcher;
use PhpAT\Rule\Event\RuleValidationEndEvent;
use PhpAT\Rule\Event\RuleValidationStartEvent;
use PhpAT\Rule\RuleCollection;
use PhpAT\Statement\Statement;
use PhpAT\Statement\StatementBuilder;
use PhpAT\Test\TestExtractor;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

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
     * @var EventSubscriberInterface
     */
    private $subscriber;
    /**
     * @var bool
     */
    private $dryRun;

    /**
     * App constructor.
     * @param TestExtractor            $extractor
     * @param StatementBuilder         $statementBuilder
     * @param EventDispatcher          $dispatcher
     * @param EventSubscriberInterface $subscriber
     */
    public function __construct(
        TestExtractor $extractor,
        StatementBuilder $statementBuilder,
        EventDispatcher $dispatcher,
        EventSubscriberInterface $subscriber
    ) {
        $this->extractor        = $extractor;
        $this->statementBuilder = $statementBuilder;
        $this->dispatcher       = $dispatcher;
        $this->subscriber       = $subscriber;
        $this->dryRun           = Configuration::getDryRun();
    }

    /**
     * @throws \Exception
     */
    public function execute(): void
    {
        $this->dispatcher->addSubscriber($this->subscriber);

        $this->dispatcher->dispatch(new SuiteStartEvent());

        $testSuite = $this->extractor->execute();

        $rules = new RuleCollection();
        foreach ($testSuite->getValues() as $test) {
            $rules = $rules->merge($test());
        }

        foreach ($rules->getValues() as $rule) {
            $statements = $this->statementBuilder->build($rule);

            $this->dispatcher->dispatch(new RuleValidationStartEvent($rule->getName()));
            /**
             * @var Statement $statement
            */
            foreach ($statements as $statement) {
                $this->validateStatement($statement);
            }

            $this->dispatcher->dispatch(new RuleValidationEndEvent());
        }

        $this->dispatcher->dispatch(new SuiteEndEvent());

        if ($this->subscriber->suiteHadErrors() && !$this->dryRun) {
            throw new \Exception();
        }
    }

    private function validateStatement(Statement $statement): void
    {
        $statement->getType()->validate(
            $statement->getParsedClass(),
            $statement->getDestinations(),
            $statement->isInverse()
        );
    }
}
