<?php

declare(strict_types=1);

namespace PHPatOld\App\Event\Listener;

use PHPatOld\App\ErrorStorage;
use PHPatOld\App\Event\WarningEvent;
use PHPatOld\EventDispatcher\EventInterface;
use PHPatOld\EventDispatcher\EventListenerInterface;

class WarningListener implements EventListenerInterface
{
    public function __invoke(EventInterface $event)
    {
        /** @var WarningEvent $event */
        ErrorStorage::addWarning($event->getMessage());
    }
}
