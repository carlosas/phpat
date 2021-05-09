<?php

namespace PhpAT\App\Cli;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Grégoire Pineau <lyrixx@lyrixx.info>
 */
class SingleCommandApplication extends Command
{
    private $version = 'UNKNOWN';
    private $autoExit = true;
    private $running = false;

    public function setVersion(string $version): self
    {
        $this->version = $version;

        return $this;
    }

    public function run(InputInterface $input = null, OutputInterface $output = null): int
    {
        if ($this->running) {
            return parent::run($input, $output);
        }

        $application = new Application($this->getName() ?: 'UNKNOWN', $this->version);
        $application->setAutoExit($this->autoExit);
        $this->setName($_SERVER['argv'][0]);
        $application->add($this);
        $application->setDefaultCommand($this->getName(), true);
        $this->running = true;

        try {
            $return = $application->run($input, $output);
        } catch (\Throwable $e) {
            $redBgWhiteText = "\033[41m\033[1;37m";
            $formattingReset = "\033[0m";
            fwrite($errStream ?? STDERR, sprintf(
                "\n%s%s%s\n",
                $redBgWhiteText,
                'An error occurred while running phpat. Please consider opening an issue: http://github.com/carlosas/phpat/issues',
                $formattingReset
            ));
            do {
                fwrite($errStream ?? STDERR, sprintf(
                    "\n%s\n%s(%s)\n\n%s",
                    $e->getMessage(),
                    $e->getFile(),
                    $e->getLine(),
                    $e->getTraceAsString()
                ));
            } while ($e = $e->getPrevious());
        } finally {
            $this->running = false;
        }

        return $return ?? 1;
    }
}
