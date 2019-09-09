<?php

namespace PhpAT\Parser;

class Declaration
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $value;

    public function __construct(string $name, string $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
