<?php

declare(strict_types=1);

namespace PhpAT\App\Event\Listener;

use PhpAT\App\Event\FatalErrorEvent;
use PhpAT\App\Exception\FatalErrorException;
use PHPAT\EventDispatcher\EventInterface;
use PHPAT\EventDispatcher\EventListenerInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FatalErrorListener implements EventListenerInterface
{
    private $output;

    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     * @param EventInterface $event
     * @throws FatalErrorException
     */
    public function __invoke(EventInterface $event)
    {
        /** @var FatalErrorEvent $event */
        $this->output->writeln('FATAL ERROR: ' . $event->getMessage());
    }
}
