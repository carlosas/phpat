<?php

declare(strict_types=1);

namespace PhpAT\App;

use PhpAT\App\Event\SuiteEndEvent;
use PhpAT\App\Event\SuiteStartEvent;
use PhpAT\Output\OutputInterface;
use PhpAT\Output\OutputLevel;
use PhpAT\Rule\Event\RuleValidationEndEvent;
use PhpAT\Rule\Event\RuleValidationStartEvent;
use PhpAT\Statement\Event\StatementNotValidEvent;
use PhpAT\Statement\Event\StatementValidEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EventSubscriber implements EventSubscriberInterface
{
    /**
     * @var OutputInterface
     */
    private $output;
    /**
     * @var ErrorStorage
     */
    private $errorStorage;

    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
        $this->errorStorage = new ErrorStorage();
    }

    public static function getSubscribedEvents(): array
    {
        return [
            SuiteStartEvent::class => 'onSuiteStartEvent',
            SuiteEndEvent::class => 'onSuiteEndEvent',
            RuleValidationStartEvent::class => 'onRuleValidationStartEvent',
            RuleValidationEndEvent::class => 'onRuleValidationEndEvent',
            StatementValidEvent::class => 'onStatementValidEvent',
            StatementNotValidEvent::class => 'onStatementNotValidEvent',
        ];
    }

    public function onSuiteStartEvent(SuiteStartEvent $event): void
    {
        $this->output->write(PHP_EOL, OutputLevel::DEFAULT);
        $this->output->writeLn('---/-------\------|-----\---/--', OutputLevel::DEFAULT);
        $this->output->writeLn('--/-PHP Architecture Tester/---', OutputLevel::DEFAULT);
        $this->output->writeLn('-/-----------\----|-------X----', OutputLevel::DEFAULT);
        $this->output->write(PHP_EOL, OutputLevel::DEFAULT);
    }

    public function onSuiteEndEvent(SuiteEndEvent $event): void
    {
        if (!$this->errorStorage->anyRuleHadErrors()) {
            $this->output->writeLn(PHP_EOL . 'phpat | TESTS PASSED', OutputLevel::DEFAULT);
        } else {
            $this->output->writeLn(PHP_EOL . 'phpat | ERRORS FOUND', OutputLevel::DEFAULT);
        }
    }

    public function onRuleValidationStartEvent(RuleValidationStartEvent $event): void
    {
        $name = $event->getRuleName();

        $this->output->write(PHP_EOL, OutputLevel::INFO);
        $this->output->writeLn(str_repeat('-', strlen($name) + 4), OutputLevel::INFO);
        $this->output->writeLn('| ' . $event->getRuleName() . ' |', OutputLevel::INFO);
        $this->output->writeLn(str_repeat('-', strlen($name) + 4), OutputLevel::INFO);
    }

    public function onRuleValidationEndEvent(RuleValidationEndEvent $event): void
    {
        $this->output->writeLn(PHP_EOL, OutputLevel::INFO);

        if (!$this->errorStorage->lastRuleHadErrors()) {
            $this->output->writeLn('OK', OutputLevel::INFO);
        }

        foreach ($this->errorStorage->flushErrors() as $error) {
            $this->output->writeLn('ERROR: ' . $error, OutputLevel::ERROR);
        }
    }

    public function onStatementValidEvent(StatementValidEvent $event): void
    {
        $this->output->write('·', OutputLevel::INFO);
        $this->output->writeLn(' ' . $event->getMessage(), OutputLevel::DEBUG);
    }

    public function onStatementNotValidEvent(StatementNotValidEvent $event): void
    {
        $this->output->write('X', OutputLevel::INFO);
        $this->output->writeLn(' ' . $event->getMessage(), OutputLevel::DEBUG);
        $this->errorStorage->addError($event->getMessage());
    }

    public function suiteHadErrors(): bool
    {
        return $this->errorStorage->anyRuleHadErrors();
    }
}
