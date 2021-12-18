<?php

declare(strict_types=1);

namespace PhpAT\App\Event\Listener;

use PhpAT\App\Event\WarningEvent;
use PhpAT\App\ErrorStorage;
use PHPAT\EventDispatcher\EventInterface;
use PHPAT\EventDispatcher\EventListenerInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WarningListener implements EventListenerInterface
{
    public function __invoke(EventInterface $event)
    {
        /** @var WarningEvent $event */
        ErrorStorage::addWarning($event->getMessage());
    }
}
