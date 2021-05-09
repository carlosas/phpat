<?php

declare(strict_types=1);

namespace PhpAT\App\Event\Listener;

use PhpAT\App\Event\WarningEvent;
use PhpAT\App\RuleValidationStorage;
use PHPAT\EventDispatcher\EventInterface;
use PHPAT\EventDispatcher\EventListenerInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WarningListener implements EventListenerInterface
{
    private $output;

    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    public function __invoke(EventInterface $event)
    {
        /** @var WarningEvent $event */
        RuleValidationStorage::addWarning($event->getMessage());
    }
}
