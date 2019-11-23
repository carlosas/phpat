<?php

declare(strict_types=1);

namespace PhpAT\App;

use PhpAT\App\Event\FatalErrorEvent;
use PhpAT\App\Event\SuiteEndEvent;
use PhpAT\App\Event\SuiteStartEvent;
use PhpAT\App\Event\WarningEvent;
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
     * @var float
     */
    private $startTime;

    /**
     * @var OutputInterface
     */
    private $output;
    /**
     * @var RuleValidationStorage
     */
    private $ruleValidationStorage;

    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
        $this->ruleValidationStorage = new RuleValidationStorage();
    }

    public static function getSubscribedEvents(): array
    {
        return [
            SuiteStartEvent::class => 'onSuiteStartEvent',
            SuiteEndEvent::class => 'onSuiteEndEvent',
            WarningEvent::class => 'onWarningEvent',
            FatalErrorEvent::class => 'onFatalErrorEvent',
            RuleValidationStartEvent::class => 'onRuleValidationStartEvent',
            RuleValidationEndEvent::class => 'onRuleValidationEndEvent',
            StatementValidEvent::class => 'onStatementValidEvent',
            StatementNotValidEvent::class => 'onStatementNotValidEvent',
        ];
    }

    public function onSuiteStartEvent(SuiteStartEvent $event): void
    {
        $this->startTime = microtime(true);
        $this->output->write(PHP_EOL, OutputLevel::DEFAULT);
        $this->output->writeLn('---/-------\------|-----\---/--', OutputLevel::DEFAULT);
        $this->output->writeLn('--/-PHP Architecture Tester/---', OutputLevel::DEFAULT);
        $this->output->writeLn('-/-----------\----|-------X----', OutputLevel::DEFAULT);
        $this->output->write(PHP_EOL, OutputLevel::DEFAULT);
    }

    public function onSuiteEndEvent(SuiteEndEvent $event): void
    {
        $reportMsg = (!$this->ruleValidationStorage->anyRuleHadErrors()) ? 'TESTS PASSED' : 'ERRORS FOUND';
        $timeMsg = round(microtime(true) - $this->startTime, 2) . 's';
        $this->output->writeLn(PHP_EOL . 'phpat | ' . $timeMsg . ' | ' . $reportMsg, OutputLevel::DEFAULT);
    }

    public function onWarningEvent(WarningEvent $event): void
    {
        $this->output->writeLn('WARNING: ' . $event->getMessage(), OutputLevel::WARNING);
    }

    public function onFatalErrorEvent(FatalErrorEvent $event): void
    {
        $this->output->writeLn('FATAL ERROR: ' . $event->getMessage(), OutputLevel::ERROR);
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

        if (!$this->ruleValidationStorage->lastRuleHadErrors()) {
            $this->output->writeLn('OK', OutputLevel::INFO);
        }

        foreach ($this->ruleValidationStorage->flushErrors() as $error) {
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
        $this->ruleValidationStorage->addError($event->getMessage());
    }

    public function suiteHadErrors(): bool
    {
        return $this->ruleValidationStorage->anyRuleHadErrors();
    }
}
