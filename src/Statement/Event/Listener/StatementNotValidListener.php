<?php

declare(strict_types=1);

namespace PhpAT\Statement\Event\Listener;

use PhpAT\App\RuleValidationStorage;
use PHPAT\EventDispatcher\EventInterface;
use PHPAT\EventDispatcher\EventListenerInterface;
use PhpAT\Output\OutputInterface;
use PhpAT\Statement\Event\StatementNotValidEvent;

class StatementNotValidListener implements EventListenerInterface
{
    private $output;

    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    public function __invoke(EventInterface $event)
    {
        /** @var StatementNotValidEvent $event */
        $this->output->statementNotValid($event->getMessage());
        RuleValidationStorage::addError($event->getMessage());
    }
}
