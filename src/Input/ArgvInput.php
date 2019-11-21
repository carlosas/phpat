<?php

declare(strict_types=1);

namespace PhpAT\Input;

class ArgvInput implements InputInterface
{
    private $args;

    private $parsed = false;

    private $parsedArguments = [];
    private $parsedOptions = [];

    private static $possibleOptions = [
        '--dry-run'
    ];

    private static $possibleArguments = [
        'config-file'
    ];

    private $currentArgument = 0;

    public function __construct()
    {
        $this->args = $_SERVER['argv'];
    }

    public function getOptions(): array
    {
        if (!$this->parsed) {
            $this->parse();
        }
        return $this->parsedOptions;
    }

    public function getArgument(string $name, $default = null): ?string
    {
        if (!$this->parsed) {
            $this->parse();
        }
        return $this->parsedArguments[$name] ?? $default;
    }

    private function parse(): void
    {
        array_shift($this->args);
        $onlyArgs = false;
        foreach ($this->args as $arg) {
            if ($onlyArgs || $arg[0] !== '-') {
                $this->parseArgument($arg);
            } elseif (strlen($arg) === 2 && $arg[1] === '-') {
                $onlyArgs = true;
            } else {
                $this->parseLongOption($arg);
            }
        }
    }

    private function parseLongOption(string $arg): void
    {
        list($option, $value) = strpos($arg, '=') !== false
            ? explode('=', $arg)
            : [$arg, true]
        ;

        if (!in_array($option, self::$possibleOptions, true)) {
            return;
        }

        $this->parsedOptions[substr($option, 2)] = $value;
    }

    private function parseArgument($arg): void
    {
        if (count(self::$possibleArguments) <= $this->currentArgument) {
            return;
        }
        $name = self::$possibleArguments[$this->currentArgument++];
        $this->parsedArguments[$name] = $arg;
    }
}
