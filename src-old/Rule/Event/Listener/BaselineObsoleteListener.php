<?php

declare(strict_types=1);

namespace PHPatOld\Rule\Event\Listener;

use PHPatOld\App\ErrorStorage;
use PHPatOld\EventDispatcher\EventInterface;
use PHPatOld\EventDispatcher\EventListenerInterface;
use PHPatOld\Rule\Event\BaselineObsoleteEvent;
use Symfony\Component\Console\Output\OutputInterface;

class BaselineObsoleteListener implements EventListenerInterface
{
    private OutputInterface $output;

    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    public function __invoke(EventInterface $event)
    {
        /** @var BaselineObsoleteEvent $event */
        $this->output->writeln(PHP_EOL . 'BASELINE ERROR: ' . $event->getMessage());
        ErrorStorage::addAnonymousError();
    }
}
