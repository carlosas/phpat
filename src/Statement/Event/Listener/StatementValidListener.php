<?php

declare(strict_types=1);

namespace PhpAT\Statement\Event\Listener;

use PHPAT\EventDispatcher\EventInterface;
use PHPAT\EventDispatcher\EventListenerInterface;
use PhpAT\Output\OutputInterface;
use PhpAT\Statement\Event\StatementValidEvent;

class StatementValidListener implements EventListenerInterface
{
    private $output;

    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    public function __invoke(EventInterface $event)
    {
        /** @var StatementValidEvent $event */
        $this->output->statementValid($event->getMessage());
    }
}
