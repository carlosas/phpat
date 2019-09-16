<?php declare(strict_types=1);

namespace PhpAT\Subscriber;

use PhpAT\Rule\Event\StatementNotValidEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EventSubscriber implements EventSubscriberInterface
{
    private $errors = false;

    public static function getSubscribedEvents(): array
    {
        return [
            StatementNotValidEvent::class => 'onStatementNotValidEvent'
        ];
    }

    public function onStatementNotValidEvent(StatementNotValidEvent $event): void
    {
        printf($event->getMessage() . PHP_EOL);
        $this->errors = true;
    }

    public function thereWereErrors(): bool
    {
        return $this->errors;
    }
}
