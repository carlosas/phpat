<?php

declare(strict_types=1);

namespace PhpAT\App\Event\Listener;

use PhpAT\App\ErrorStorage;
use PhpAT\App\Event\WarningEvent;
use PHPAT\EventDispatcher\EventInterface;
use PHPAT\EventDispatcher\EventListenerInterface;

class WarningListener implements EventListenerInterface
{
    public function __invoke(EventInterface $event)
    {
        /** @var WarningEvent $event */
        ErrorStorage::addWarning($event->getMessage());
    }
}
