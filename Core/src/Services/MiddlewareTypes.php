<?php

namespace Core\Services;

enum MiddlewareTypes
{
    case REQUEST_CREATED;
    case ROUTER_INITIALIZED;
    case CONTROLLER_MATCHED;
    case CONTROLLER_EXECUTED;
    case RESPONSE_CREATED;
}
