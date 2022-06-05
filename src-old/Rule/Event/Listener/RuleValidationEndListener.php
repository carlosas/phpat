<?php

declare(strict_types=1);

namespace PHPatOld\Rule\Event\Listener;

use PHPatOld\App\ErrorStorage;
use PHPatOld\EventDispatcher\EventInterface;
use PHPatOld\EventDispatcher\EventListenerInterface;
use PHPatOld\Rule\Baseline;
use PHPatOld\Rule\RuleContext;
use Symfony\Component\Console\Output\OutputInterface;

class RuleValidationEndListener implements EventListenerInterface
{
    private OutputInterface $output;
    private Baseline $baseline;

    public function __construct(OutputInterface $output, Baseline $baseline)
    {
        $this->output   = $output;
        $this->baseline = $baseline;
    }

    public function __invoke(EventInterface $event)
    {
        $this->output->writeln('', OutputInterface::VERBOSITY_VERBOSE);
        foreach (ErrorStorage::flushWarnings() as $warning) {
            $this->output->writeln('WARNING: ' . $warning, OutputInterface::VERBOSITY_NORMAL);
        }

        $errors = ErrorStorage::flushRuleErrors();
        if (empty($errors)) {
            $this->output->writeln('OK', OutputInterface::VERBOSITY_VERBOSE);
            return;
        }
        foreach ($errors as $error) {
            $this->baseline->storeError(RuleContext::ruleName(), $error);
            $this->output->writeln('ERROR: ' . $error, OutputInterface::VERBOSITY_NORMAL);
        }
    }
}
