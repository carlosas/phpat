<?php

declare(strict_types=1);

namespace PhpAT\Rule\Event\Listener;

use PHPAT\EventDispatcher\EventInterface;
use PHPAT\EventDispatcher\EventListenerInterface;
use PhpAT\Output\OutputInterface;
use PhpAT\Output\OutputLevel;

class RuleValidationStartListener implements EventListenerInterface
{
    private $output;

    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    public function __invoke(EventInterface $event)
    {
        $name = $event->getRuleName();

        $this->output->write(PHP_EOL, OutputLevel::INFO);
        $this->output->writeLn(str_repeat('-', strlen($name) + 4), OutputLevel::INFO);
        $this->output->writeLn('| ' . $event->getRuleName() . ' |', OutputLevel::INFO);
        $this->output->writeLn(str_repeat('-', strlen($name) + 4), OutputLevel::INFO);
    }
}
