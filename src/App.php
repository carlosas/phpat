<?php

declare(strict_types=1);

namespace PhpAT;

use PhpAT\Output\OutputInterface;
use PhpAT\Rule\Rule;
use PhpAT\Rule\RuleCollection;
use PhpAT\Shared\EventSubscriber;
use PhpAT\Statement\Statement;
use PhpAT\Statement\StatementBuilder;
use PhpAT\Test\TestExtractor;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class App
{
    /** @var TestExtractor $extractor */
    private $extractor;
    /** @var StatementBuilder $statementBuilder */
    private $statementBuilder;
    /** @var EventDispatcherInterface */
    private $dispatcher;
    /** @var EventSubscriber */
    private $subscriber;
    /** @var OutputInterface */
    private $output;

    /**
     * App constructor.
     *
     * @param TestExtractor            $extractor
     * @param StatementBuilder         $statementBuilder
     * @param EventDispatcherInterface $dispatcher
     * @param EventSubscriberInterface $subscriber
     * @param OutputInterface          $output
     */
    public function __construct(
        TestExtractor $extractor,
        StatementBuilder $statementBuilder,
        EventDispatcherInterface $dispatcher,
        EventSubscriberInterface $subscriber,
        OutputInterface $output
    ) {
        $this->extractor        = $extractor;
        $this->statementBuilder = $statementBuilder;
        $this->dispatcher       = $dispatcher;
        $this->subscriber       = $subscriber;
        $this->output           = $output;
    }

    /** @throws \Exception */
    public function execute(): void
    {
        try {
            $this->dispatcher->addSubscriber($this->subscriber);

            $testSuite = $this->extractor->execute();

            $rules = new RuleCollection();
            foreach ($testSuite->getValues() as $test) {
                $rules = $rules->merge($test());
            }
            $this->exposeLogo();

            foreach ($rules->getValues() as $rule) {
                $statements = $this->statementBuilder->build($rule);
                $this->exposeRuleName($rule);
                /** @var Statement $statement */
                foreach ($statements as $statement) {
                    $this->validateStatement($statement);
                }
                $this->output->writeLn("");
            }
        } catch (\Exception $e) {
            $this->exposeFatalAndExit($e->getMessage());
        }

        if ($this->subscriber->thereWereErrors()) {
            throw new \Exception();
        }
    }

    private function exposeLogo(): void
    {
        $this->output->writeLn('---/-------\------|-----\---/--');
        $this->output->writeLn('--/-PHP Architecture Tester/---');
        $this->output->writeLn('-/-----------\----|-------X----');
        $this->output->writeLn("");
    }

    private function exposeRuleName(Rule $rule): void
    {
        $this->output->writeLn('RULE: ' . $rule->getName());
    }

    private function validateStatement(Statement $statement): void
    {
        $statement->getType()->validate(
            $statement->getParsedClass(),
            $statement->getDestinations(),
            $statement->isInverse()
        );
    }

    /**
     * @throws \Exception
     */
    private function exposeFatalAndExit(string $message, string $trace = null): void
    {
        $errormsg = 'FATAL ERROR: ' . $message;
        if (!is_null($trace)) {
            $errormsg .= ' in ' . $trace;
        }
        $this->output->writeLn($errormsg, $error = true);

        throw new \Exception();
    }
}
