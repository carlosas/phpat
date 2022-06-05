<?php

namespace PHPatOld\App;

use PHPatOld\App\Event\FatalErrorEvent;
use PHPatOld\App\Event\Listener\FatalErrorListener;
use PHPatOld\App\Event\Listener\SuiteEndListener;
use PHPatOld\App\Event\Listener\SuiteStartListener;
use PHPatOld\App\Event\Listener\WarningListener;
use PHPatOld\App\Event\SuiteEndEvent;
use PHPatOld\App\Event\SuiteStartEvent;
use PHPatOld\App\Event\WarningEvent;
use PHPatOld\EventDispatcher\ListenerProvider;
use PHPatOld\Rule\Event\BaselineObsoleteEvent;
use PHPatOld\Rule\Event\Listener\BaselineObsoleteListener;
use PHPatOld\Rule\Event\Listener\RuleValidationEndListener;
use PHPatOld\Rule\Event\Listener\RuleValidationStartListener;
use PHPatOld\Rule\Event\RuleValidationEndEvent;
use PHPatOld\Rule\Event\RuleValidationStartEvent;
use PHPatOld\Statement\Event\Listener\StatementNotValidListener;
use PHPatOld\Statement\Event\Listener\StatementValidListener;
use PHPatOld\Statement\Event\StatementNotValidEvent;
use PHPatOld\Statement\Event\StatementValidEvent;

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
