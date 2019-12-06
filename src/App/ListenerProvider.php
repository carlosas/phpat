<?php

declare(strict_types=1);

namespace PhpAT\App;

use PhpAT\App\Event\Listener\FatalErrorListener;
use PhpAT\App\Event\Listener\SuiteEndListener;
use PhpAT\App\Event\Listener\SuiteStartListener;
use PhpAT\App\Event\Listener\WarningListener;
use PhpAT\Output\OutputInterface;
use PhpAT\Rule\Event\Listener\RuleValidationEndListener;
use PhpAT\Rule\Event\Listener\RuleValidationStartListener;
use PhpAT\Statement\Event\Listener\StatementNotValidListener;
use PhpAT\Statement\Event\Listener\StatementValidListener;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ListenerProvider
{
    private $builder;

    public function __construct(ContainerBuilder $builder)
    {
        $this->builder  = $builder;
    }

    public function register(): ContainerBuilder
    {
        $this->builder
            ->register(SuiteStartListener::class, SuiteStartListener::class)
            ->addArgument(new Reference(OutputInterface::class));

        $this->builder
            ->register(SuiteEndListener::class, SuiteEndListener::class)
            ->addArgument(new Reference(OutputInterface::class));

        $this->builder
            ->register(WarningListener::class, WarningListener::class)
            ->addArgument(new Reference(OutputInterface::class));

        $this->builder
            ->register(FatalErrorListener::class, FatalErrorListener::class)
            ->addArgument(new Reference(OutputInterface::class));

        $this->builder
            ->register(RuleValidationStartListener::class, RuleValidationStartListener::class)
            ->addArgument(new Reference(OutputInterface::class));

        $this->builder
            ->register(RuleValidationEndListener::class, RuleValidationEndListener::class)
            ->addArgument(new Reference(OutputInterface::class));

        $this->builder
            ->register(StatementValidListener::class, StatementValidListener::class)
            ->addArgument(new Reference(OutputInterface::class));

        $this->builder
            ->register(StatementNotValidListener::class, StatementNotValidListener::class)
            ->addArgument(new Reference(OutputInterface::class));

        return $this->builder;
    }
}
