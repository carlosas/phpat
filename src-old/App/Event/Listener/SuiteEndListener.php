<?php

declare(strict_types=1);

namespace PHPatOld\App\Event\Listener;

use PHPatOld\App\Configuration;
use PHPatOld\App\ErrorStorage;
use PHPatOld\EventDispatcher\EventInterface;
use PHPatOld\EventDispatcher\EventListenerInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SuiteEndListener implements EventListenerInterface
{
    private OutputInterface $output;
    private bool $generatingBaseline;

    public function __construct(OutputInterface $output, Configuration $configuration)
    {
        $this->output             = $output;
        $this->generatingBaseline = $configuration->getGenerateBaseline() !== null;
    }

    public function __invoke(EventInterface $event)
    {
        $errors = ErrorStorage::getTotalErrors();
        $time   = microtime(true) - ErrorStorage::getStartTime();

        $this->output->writeln('', OutputInterface::VERBOSITY_NORMAL);

        $message = $this->buildMessage($errors);

        $this->output->writeln(
            ' <options=bold>phpat</> | ' . round($time, 2) . 's' . ' | ' . $message,
            OutputInterface::VERBOSITY_NORMAL
        );
    }

    private function buildMessage(int $errors): string
    {
        if ($this->generatingBaseline) {
            return '<comment>BASELINE GENERATED</comment>';
        }

        return $errors === 0
            ? '<info>TESTS PASSED</info>'
            : '<error>ERRORS FOUND</error>';
    }
}
