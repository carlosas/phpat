<?php

declare(strict_types=1);

namespace PhpAT\App\Event\Listener;

use PhpAT\App\RuleValidationStorage;
use PHPAT\EventDispatcher\EventInterface;
use PHPAT\EventDispatcher\EventListenerInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SuiteEndListener implements EventListenerInterface
{
    private $output;

    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    public function __invoke(EventInterface $event)
    {
        $errors = RuleValidationStorage::getTotalErrors();
        $time = microtime(true) - RuleValidationStorage::getStartTime();

        $this->output->writeln('', OutputInterface::VERBOSITY_NORMAL);
        $message = $errors === 0
            ? '<info>TESTS PASSED</info>'
            : '<error>ERRORS FOUND</error>';
        $this->output->writeln(
            ' <options=bold>phpat</> | ' . round($time, 2) . 's' . ' | ' . $message,
            OutputInterface::VERBOSITY_NORMAL
        );
    }
}
