<?php

namespace PhpAT\App;

use Symfony\Component\EventDispatcher\Event as ComponentEvent;
use Symfony\Contracts\EventDispatcher\Event as ContractEvent;

if (class_exists(ContractEvent::class)) {
    abstract class Event extends ContractEvent
    {
    }
} else {
    abstract class Event extends ComponentEvent
    {
    }
}
