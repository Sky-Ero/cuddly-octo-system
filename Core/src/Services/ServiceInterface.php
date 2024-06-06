<?php
namespace Core\Services;


/*  service interface
    service should be a singleton
    service should not have constructor
    service should be cachable
*/

interface ServiceInterface
{
    // public function __construct();

    public function run();

    public static function getInstance();
}

