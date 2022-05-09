<?php declare(strict_types=1);

namespace Tests\PhpAT\functional;

use PhpAT\Test\TestParser;

class FakeTestParser extends TestParser
{
    /** @var class-string */
    private string $assertion;
    /** @var array<class-string> */
    private array $subjects;
    /** @var array<class-string> */
    private array $targets;

    /**
     * @param class-string $assertion
     * @param array<class-string> $subjects
     * @param array<class-string> $targets
     */
    public function __construct(string $assertion, array $subjects, array $targets)
    {
        $this->assertion = $assertion;
        $this->subjects = $subjects;
        $this->targets = $targets;
    }

    public function __invoke()
    {
        return [
            [
                'assertion' => $this->assertion,
                'subjects' => $this->subjects,
                'targets' => $this->targets,
            ],
        ];
    }
}
