<?php

namespace TestApp;

use Core\EventListener;
use Core\DefinedRouteEvent;

class DefinedRouteEventListener extends EventListener
{

    public function onEvent(DefinedRouteEvent $event): mixed
    {

        return null;
    }
} 