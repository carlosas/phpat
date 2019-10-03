<?php

declare(strict_types=1);

namespace PhpAT\Shared;

use PhpAT\Output\OutputInterface;
use PhpAT\Statement\Event\StatementNotValidEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EventSubscriber implements EventSubscriberInterface
{
    private $errors = false;
    /**
     * @var OutputInterface
     */
    private $output;

    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            StatementNotValidEvent::class => 'onStatementNotValidEvent',
        ];
    }

    public function onStatementNotValidEvent(StatementNotValidEvent $event): void
    {
        $this->output->writeLn('ERROR: '.$event->getMessage(), $error = true);
        $this->errors = true;
    }

    public function thereWereErrors(): bool
    {
        return $this->errors;
    }
}
