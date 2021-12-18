<?php

namespace PhpAT\App;

use PhpAT\App\Event\FatalErrorEvent;
use PhpAT\App\Event\Listener\FatalErrorListener;
use PhpAT\App\Event\Listener\SuiteEndListener;
use PhpAT\App\Event\Listener\WarningListener;
use PhpAT\App\Event\SuiteEndEvent;
use PhpAT\App\Event\SuiteStartEvent;
use PhpAT\App\Event\Listener\SuiteStartListener;
use PhpAT\App\Event\WarningEvent;
use PHPAT\EventDispatcher\ListenerProvider;
use PhpAT\Rule\Event\BaselineObsoleteEvent;
use PhpAT\Rule\Event\Listener\BaselineObsoleteListener;
use PhpAT\Rule\Event\Listener\RuleValidationEndListener;
use PhpAT\Rule\Event\Listener\RuleValidationStartListener;
use PhpAT\Rule\Event\RuleValidationEndEvent;
use PhpAT\Rule\Event\RuleValidationStartEvent;
use PhpAT\Statement\Event\Listener\StatementNotValidListener;
use PhpAT\Statement\Event\Listener\StatementValidListener;
use PhpAT\Statement\Event\StatementNotValidEvent;
use PhpAT\Statement\Event\StatementValidEvent;

class EventListenerMapper
{
    public function populateListenerProvider(ListenerProvider $listenerProvider): ListenerProvider
    {
        $listenerProvider->addEventListener(SuiteStartEvent::class, SuiteStartListener::class);
        $listenerProvider->addEventListener(SuiteEndEvent::class, SuiteEndListener::class);
        $listenerProvider->addEventListener(WarningEvent::class, WarningListener::class);
        $listenerProvider->addEventListener(FatalErrorEvent::class, FatalErrorListener::class);
        $listenerProvider->addEventListener(RuleValidationStartEvent::class, RuleValidationStartListener::class);
        $listenerProvider->addEventListener(RuleValidationEndEvent::class, RuleValidationEndListener::class);
        $listenerProvider->addEventListener(StatementValidEvent::class, StatementValidListener::class);
        $listenerProvider->addEventListener(StatementNotValidEvent::class, StatementNotValidListener::class);
        $listenerProvider->addEventListener(BaselineObsoleteEvent::class, BaselineObsoleteListener::class);

        return $listenerProvider;
    }
}
