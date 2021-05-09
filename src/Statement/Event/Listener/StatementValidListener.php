<?php

declare(strict_types=1);

namespace PhpAT\Statement\Event\Listener;

use PHPAT\EventDispatcher\EventInterface;
use PHPAT\EventDispatcher\EventListenerInterface;
use PhpAT\Statement\Event\StatementValidEvent;
use Symfony\Component\Console\Output\OutputInterface;

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
        $this->output->write('.', false, OutputInterface::VERBOSITY_VERBOSE);
        $this->output->writeln(' ' . $event->getMessage(), OutputInterface::VERBOSITY_VERY_VERBOSE);
    }
}
