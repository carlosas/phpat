<?php

declare(strict_types=1);

namespace PhpAT\Rule\Event\Listener;

use PHPAT\EventDispatcher\EventInterface;
use PHPAT\EventDispatcher\EventListenerInterface;
use PhpAT\Output\OutputInterface;

class RuleValidationStartListener implements EventListenerInterface
{
    private $output;

    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    public function __invoke(EventInterface $event)
    {
        $this->output->ruleValidationStart($event->getRuleName());
    }
}
