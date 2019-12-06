<?php

declare(strict_types=1);

namespace PhpAT\App\Event\Listener;

use PhpAT\App\RuleValidationStorage;
use PHPAT\EventDispatcher\EventInterface;
use PHPAT\EventDispatcher\EventListenerInterface;
use PhpAT\Output\OutputInterface;
use PhpAT\Output\OutputLevel;

class SuiteEndListener implements EventListenerInterface
{
    private $output;

    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    public function __invoke(EventInterface $event)
    {
        $reportMsg = (!RuleValidationStorage::anyRuleHadErrors()) ? 'TESTS PASSED' : 'ERRORS FOUND';
        $timeMsg = round(microtime(true) - RuleValidationStorage::getStartTime(), 2) . 's';
        $this->output->writeLn(PHP_EOL . 'phpat | ' . $timeMsg . ' | ' . $reportMsg, OutputLevel::DEFAULT);
    }
}
