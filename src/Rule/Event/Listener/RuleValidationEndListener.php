<?php

declare(strict_types=1);

namespace PhpAT\Rule\Event\Listener;

use PhpAT\App\RuleValidationStorage;
use PHPAT\EventDispatcher\EventInterface;
use PHPAT\EventDispatcher\EventListenerInterface;
use PhpAT\Output\OutputInterface;
use PhpAT\Output\OutputLevel;

class RuleValidationEndListener implements EventListenerInterface
{
    private $output;

    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    public function __invoke(EventInterface $event)
    {
        $this->output->writeLn(PHP_EOL, OutputLevel::INFO);

        if (!RuleValidationStorage::lastRuleHadErrors()) {
            $this->output->writeLn('OK', OutputLevel::INFO);
        }

        foreach (RuleValidationStorage::flushErrors() as $error) {
            $this->output->writeLn('ERROR: ' . $error, OutputLevel::ERROR);
        }
    }
}
