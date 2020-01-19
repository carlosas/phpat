<?php

declare(strict_types=1);

namespace PhpAT\App\Event\Listener;

use PhpAT\App\RuleValidationStorage;
use PHPAT\EventDispatcher\EventInterface;
use PHPAT\EventDispatcher\EventListenerInterface;
use PhpAT\Output\OutputInterface;

class SuiteEndListener implements EventListenerInterface
{
    private $output;

    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    public function __invoke(EventInterface $event)
    {
        $success = !RuleValidationStorage::anyRuleHadErrors();
        $time = microtime(true) - RuleValidationStorage::getStartTime();
        $this->output->suiteEnd($time, $success);
    }
}
