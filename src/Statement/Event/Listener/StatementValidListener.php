<?php

declare(strict_types=1);

namespace PhpAT\Statement\Event\Listener;

use PHPAT\EventDispatcher\EventInterface;
use PHPAT\EventDispatcher\EventListenerInterface;
use PhpAT\Output\OutputInterface;
use PhpAT\Output\OutputLevel;

class StatementValidListener implements EventListenerInterface
{
    private $output;

    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    public function __invoke(EventInterface $event)
    {
        $this->output->write('.', OutputLevel::INFO);
        $this->output->writeLn(' ' . $event->getMessage(), OutputLevel::DEBUG);
    }
}
