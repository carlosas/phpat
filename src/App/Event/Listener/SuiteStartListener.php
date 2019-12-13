<?php

declare(strict_types=1);

namespace PhpAT\App\Event\Listener;

use PhpAT\App\RuleValidationStorage;
use PHPAT\EventDispatcher\EventInterface;
use PHPAT\EventDispatcher\EventListenerInterface;
use PhpAT\Output\OutputInterface;
use PhpAT\Output\OutputLevel;

class SuiteStartListener implements EventListenerInterface
{
    private $output;

    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    public function __invoke(EventInterface $event)
    {
        RuleValidationStorage::setStartTime(microtime(true));
        $this->output->write(PHP_EOL, OutputLevel::DEFAULT);
        $this->output->writeLn('---/-------\------|-----\---/--', OutputLevel::DEFAULT);
        $this->output->writeLn('--/-PHP Architecture Tester/---', OutputLevel::DEFAULT);
        $this->output->writeLn('-/-----------\----|-------X----', OutputLevel::DEFAULT);
        $this->output->write(PHP_EOL, OutputLevel::DEFAULT);
    }
}
