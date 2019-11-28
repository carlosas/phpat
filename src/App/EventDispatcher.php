<?php

namespace PhpAT\App;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\LegacyEventDispatcherProxy;

/**
 * @internal
 */
final class EventDispatcher
{
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param EventDispatcherInterface $eventDispatcher
     * @param object $event
     */
    public function dispatch($event)
    {
        $name = get_class($event);

        if (class_exists(LegacyEventDispatcherProxy::class)) {
            return $this->dispatcher->dispatch($event, $name);
        }

        return $this->dispatcher->dispatch($name, $event);
    }

    public function addSubscriber(EventSubscriberInterface $subscriber)
    {
        return $this->dispatcher->addSubscriber($subscriber);
    }
}
