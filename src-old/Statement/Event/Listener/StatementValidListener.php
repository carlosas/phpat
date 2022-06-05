<?php

declare(strict_types=1);

namespace PHPatOld\Statement\Event\Listener;

use PHPatOld\EventDispatcher\EventInterface;
use PHPatOld\EventDispatcher\EventListenerInterface;
use PHPatOld\Statement\Event\StatementValidEvent;
use Symfony\Component\Console\Output\OutputInterface;

class StatementValidListener implements EventListenerInterface
{
    private OutputInterface $output;

    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     * @psalm-suppress MoreSpecificImplementedParamType
     * @param StatementValidEvent $event
     */
    public function __invoke(EventInterface $event)
    {
        $this->output->write('.', false, OutputInterface::VERBOSITY_VERBOSE);
        $this->output->writeln(' ' . $event->getMessage(), OutputInterface::VERBOSITY_VERY_VERBOSE);
    }
}
