<?php

declare(strict_types=1);

namespace PHPatOld\App\Event\Listener;

use PHPatOld\App\Event\FatalErrorEvent;
use PHPatOld\EventDispatcher\EventInterface;
use PHPatOld\EventDispatcher\EventListenerInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FatalErrorListener implements EventListenerInterface
{
    private OutputInterface $output;

    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    public function __invoke(EventInterface $event)
    {
        /** @var FatalErrorEvent $event */
        $this->output->writeln('FATAL ERROR: ' . $event->getMessage());
    }
}
