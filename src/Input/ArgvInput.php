<?php

declare(strict_types=1);

namespace PhpAT\Input;

class ArgvInput implements InputInterface
{
    private $args;

    private $parsedArguments = [];
    private $parsedOptions = [];

    private static $possibleOptions = [
        '--dry-run' => 'bool',
        '--verbosity' => 'int'
    ];

    private static $possibleArguments = [
        'config-file'
    ];

    private $currentArgument = 0;

    public function __construct(array $args)
    {
        $this->args = $args;
        $this->parse();
    }

    public function getOptions(): array
    {
        return $this->parsedOptions;
    }

    public function getOption(string $name, $default = null)
    {
        return $this->parsedOptions[$name] ?? $default;
    }

    public function getArgument(string $name, $default = null): ?string
    {
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

        if (!in_array($option, array_keys(self::$possibleOptions), true)) {
            return;
        }

        settype($value, self::$possibleOptions[$option]);
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
