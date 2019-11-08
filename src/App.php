<?php

declare(strict_types=1);

namespace PhpAT;

use PhpAT\App\Event\SuiteEndEvent;
use PhpAT\App\Event\SuiteStartEvent;
use PhpAT\Rule\Event\RuleValidationEndEvent;
use PhpAT\Rule\Event\RuleValidationStartEvent;
use PhpAT\Rule\RuleCollection;
use PhpAT\Statement\Statement;
use PhpAT\Statement\StatementBuilder;
use PhpAT\Test\TestExtractor;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
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
     * @var EventDispatcherInterface 
     */
    private $dispatcher;
    /**
     * @var EventSubscriberInterface 
     */
    private $subscriber;

    /**
     * App constructor.
     *
     * @param TestExtractor            $extractor
     * @param StatementBuilder         $statementBuilder
     * @param EventDispatcherInterface $dispatcher
     * @param EventSubscriberInterface $subscriber
     */
    public function __construct(
        TestExtractor $extractor,
        StatementBuilder $statementBuilder,
        EventDispatcherInterface $dispatcher,
        EventSubscriberInterface $subscriber
    ) {
        $this->extractor        = $extractor;
        $this->statementBuilder = $statementBuilder;
        $this->dispatcher       = $dispatcher;
        $this->subscriber       = $subscriber;
    }

    /**
     * @throws \Exception 
     */
    public function execute(): void
    {
        $this->dispatcher->addSubscriber($this->subscriber);

        $this->dispatcher->dispatch(SuiteStartEvent::class, new SuiteStartEvent());

        $testSuite = $this->extractor->execute();

        $rules = new RuleCollection();
        foreach ($testSuite->getValues() as $test) {
            $rules = $rules->merge($test());
        }

        foreach ($rules->getValues() as $rule) {
            $statements = $this->statementBuilder->build($rule);
            $this->dispatcher->dispatch(
                RuleValidationStartEvent::class,
                new RuleValidationStartEvent($rule->getName())
            );
            /**
             * @var Statement $statement
            */
            foreach ($statements as $statement) {
                $this->validateStatement($statement);
            }
            $this->dispatcher->dispatch(RuleValidationEndEvent::class, new RuleValidationEndEvent());
        }

        $this->dispatcher->dispatch(SuiteEndEvent::class, new SuiteEndEvent());

        if ($this->subscriber->suiteHadErrors()) {
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
